<?php

namespace App\Http\Controllers\Admin\Contract\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Request\ExportRequest;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractHistory;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Contract\V1\Request\ContractRequest;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use DB;


class ContractController extends Controller
{
    public function show(Contract $contract)
    {
        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(200);
    }

    public function index(ContractRequest $request, Contract $contract)
    {

        $query = $contract->query();
        if ($request->filled('start_date') && $request->get('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }

        if ($request->filled('name')) {
            $name = $request->get('name');

            $query->whereHas('company', static function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($request->filled('owner')) {
            $query->where('owner', '=', $request->get('owner'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
        }

        if ($request->filled('product_status')) {
            $query->where('product_status', $request->get('product_status'));
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user|bd-manager')) {
            $query->whereRaw("(owner = $user->id or handler = $user->id)");
        } elseif ($user->hasRole('legal-affairs|legal-affairs-manager')) {
            $query->whereRaw("(owner = $user->id or handler = $user->id or status=3)");
        } elseif ($user->hasRole('purchasing')) {
            //角色为采购时，查询条件为：已审批完成(status=3),product_status为非0（1未出厂or2已出厂）
            $query->whereRaw('(status = 3 and product_status != 0)');
        } else {
            $query->where('status', ActionConfig::CONTRACT_STATUS_AGREE);
        }
        $contracts = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($contracts, new ContractTransformer())->setStatusCode(200);
    }


    public function store(ContractRequest $request, Contract $contract)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->parent_id && $user->hasRole('user|bd-manager')) {
            abort(500, '无所属主管，无法新增合同申请');
        }

        $product_status = ActionConfig::CONTRACT_PRODUCT_STATUS_NOHARDWARE;
        //收款合同且不是服务类型
        if ($request->get('type') === ActionConfig::CONTRACT_TYPE_RECEIVE && $request->get('kind') !== ActionConfig::CONTRACT_KIND_SERVE) {
            $product_status = ActionConfig::CONTRACT_PRODUCT_STATUS_NOTOUT;
        }
        $param = $request->all();
        //不是收款合同默认0
        if ($request->get('type') !== ActionConfig::CONTRACT_TYPE_RECEIVE) {
            $param['kind'] = 0;
            $param['special_num'] = 0;
            $param['common_num'] = 0;
        }

        try {
            DB::beginTransaction();

            //法务和法务主管建的直接已审批
            if ($user->hasRole('legal-affairs|legal-affairs-manager')) {
                $rest = [
                    'applicant' => $user->id,
                    'owner' => $user->id,
                    'status' => ActionConfig::CONTRACT_STATUS_AGREE,
                    'handler' => null,
                    'product_status' => $product_status
                ];
            } else {
                $legalId = getProcessStaffId('legal-affairs', 'contract');
                $rest = [
                    'applicant' => $user->id,
                    'owner' => $user->id,
                    'status' => ActionConfig::CONTRACT_STATUS_WAIT,
                    'handler' => $legalId,
                    'product_status' => $product_status
                ];
            }
            $contract->fill(array_merge($param, $rest))->save();

            //文档存储
            $ids = explode(',', $request->get('ids'));
            foreach ($ids as $id) {
                $contract->media()->attach($id);
            }

            //收款日期存储
            if ($request->get('type') === ActionConfig::CONTRACT_TYPE_RECEIVE && $request->has('receive_date')) {
                $dates = explode(',', $request->get('receive_date'));
                foreach ($dates as $date) {
                    ContractReceiveDate::create(['contract_id' => $contract->id, 'receive_date' => $date, 'receive_status' => 0]);
                }
            }

            //硬件存储
            if ($request->get('kind') !== ActionConfig::CONTRACT_KIND_SERVE && $request->has('product_content')) {
                $param = $request->all();
                $content = $param['product_content'];
                foreach ($content as $item) {
                    $item['contract_id'] = $contract->id;
                    ContractProduct::query()->create($item);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            abort(500, '系统错误');
        }

        activity('create_contract')
            ->causedBy($user)
            ->performedOn($contract)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增合同申请');

        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    public function update(ContractRequest $request, Contract $contract): Response
    {
        if (!($contract->status === ActionConfig::CONTRACT_STATUS_AGREE && $contract->type === ActionConfig::CONTRACT_TYPE_RECEIVE)) {
            abort(403, '不可更改');
        }

        $contract->update($request->all());

        activity('update_contract')
            ->causedBy($this->user())
            ->performedOn($contract)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑合同申请');

        return $this->response()->noContent();

    }

    public function destroy(Contract $contract, Request $request)
    {
        if ($contract->status !== ActionConfig::CONTRACT_STATUS_WAIT) {
            abort(403, '合同审批状态已更改，不可删除');
        }
        $contract->delete();


        activity('delete_contract')
            ->causedBy($this->user())
            ->performedOn($contract)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => []])
            ->log('删除合同申请');

        return $this->response()->noContent()->setStatusCode(204);
    }

    public function reject(Request $request, Contract $contract)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        //法务驳回可以修改文件
        if ($user->hasRole('legal-affairs')) {
            $ids = explode(',', $request->get('ids'));
            $contract->media()->detach();
            foreach ($ids as $id) {
                $contract->media()->attach($id);
            }
        }
        $contract->update(array_merge($request->all(), ['status' => ActionConfig::CONTRACT_STATUS_REJECT, 'handler' => $contract->applicant]));
        ContractHistory::updateOrCreate(['user_id' => $user->id, 'contract_id' => $contract->id], ['user_id' => $user->id, 'contract_id' => $contract->id]);

        activity('reject_contract')
            ->causedBy($user)
            ->performedOn($contract)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('驳回合同申请');

        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(200);
    }

    public function auditing(Request $request, Contract $contract)
    {
        /**@var $user \App\Models\User */
        $user = $this->user();
        abort_if($user->id !== $contract->handler, 403, '无审批权限');

        if ($user->hasRole('legal-affairs')) {
            $params = [
                'legal_message',
                'contract_number'
            ];
            $this->checkParam($request, $params, $contract);
            $contract->fill(array_merge($request->all(), ['status' => ActionConfig::CONTRACT_STATUS_ONGOING, 'handler' => $user->parent_id]));
            $this->updateContractAndHistory($user, $contract);
        } else if ($user->hasRole('legal-affairs-manager')) {
            $params = [
                'legal_ma_message',
                'special_num',
                'common_num'
            ];
            $this->checkParam($request, $params, $contract);
            //特批合同需要带合同编号
            if ($contract->status === ActionConfig::CONTRACT_STATUS_SPECIAL) {
                $this->checkParam($request, ['contract_number'], $contract);
            }

            $parentId = $contract->applicantUser->parent_id;
            // BD主管建的直接已审批,不经过自己
            if ($parentId === $contract->owner) {
                $contract->status = ActionConfig::CONTRACT_STATUS_AGREE;
                $contract->handler = null;
            } else {
                $contract->status = ActionConfig::CONTRACT_STATUS_ONGOING;
                $contract->handler = $parentId;
            }

            $contract->fill($request->all());
            $this->updateContractAndHistory($user, $contract);
        } else if ($user->hasRole('bd-manager')) {
            $params = [
                'bd_ma_message'
            ];
            $this->checkParam($request, $params, $contract);

            $contract->status = ActionConfig::CONTRACT_STATUS_AGREE;
            $contract->handler = null;
            $contract->bd_ma_message = $request->get('bd_ma_message');
            $this->updateContractAndHistory($user, $contract);
        }

        activity('audit_contract')
            ->causedBy($user)
            ->performedOn($contract)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('审批合同申请');

        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    private function checkParam(Request $request, array $param, Contract $contract): void
    {
        if (!$request->has($param)) {
            abort(422, '请填写完整信息');
        }

        if ($request->has('contract_number')) {
            $existContract = Contract::query()
                ->where('contract_number', $request->get('contract_number'))
                ->where('id', '<>', $contract->id)
                ->first();
            abort_if($existContract, 422, '合同编号已存在');
        }
    }

    private function updateContractAndHistory(User $user, Contract $contract): void
    {
        $contract->update();
        $data = [
            'user_id' => $user->id,
            'contract_id' => $contract->id
        ];
        ContractHistory::updateOrCreate($data, $data);
    }

    public function specialAuditing(Request $request, Contract $contract): Response
    {
        $role = Role::findByName('legal-affairs-manager');
        $legalManager = $role->users()->first();

        if ($contract->status === ActionConfig::CONTRACT_STATUS_ONGOING) {
            abort(403, '合同审核中无法申请特批');
        }
        $contract->status = ActionConfig::CONTRACT_STATUS_SPECIAL;
        $contract->handler = $legalManager->id;
        $contract->update();

        activity('special_audit_contract')
            ->causedBy($this->user())
            ->performedOn($contract)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('特批合同申请');

        return $this->response()->noContent();
    }

    public function count(Request $request)
    {
        $user = $this->user();
        $contractCount = Contract::query()->where('handler', $user->id)->where('status', '<>', 5)->count();
        $invoiceCount = Invoice::query()->where('handler', $user->id)->where('status', '<>', 6)->count();
        $paymentCount = Payment::query()->where('handler', $user->id)->where('status', '<>', 5)->count();
        $data = [
            'contract_count' => $contractCount,
            'invoice_count' => $invoiceCount,
            'payment_Count' => $paymentCount
        ];
        return response()->json($data);
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'contract');
    }

    public function filed(Request $request, Contract $contract): Response
    {
        /** @var User $user */
        $user = $this->user();
        if (!$user->hasRole('legal-affairs|legal-affairs-manager')) {
            abort(403, '无操作权限');
        }
        if ($contract->status !== ActionConfig::CONTRACT_STATUS_AGREE) {
            abort(403, '此操作只适用于已审批合同');
        }

        if ($request->filled('filed_date')) {
            $contract->update(['filed_date' => $request->get('filed_date')]);
        }
        return $this->response()->noContent();
    }

    public function revenueExport(ExportRequest $request)
    {
        return excelExport($request);
    }
}
