<?php

namespace App\Http\Controllers\Admin\Contract\V1\Api;

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
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class ContractController extends Controller
{
    public function show(Contract $contract)
    {
        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(200);
    }

    public function index(ContractRequest $request, Contract $contract)
    {

        $query = $contract->query();
        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ");
        }

        if ($request->name) {
            $name = $request->name;
            $query->whereHas('company', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }
//        if ($request->name) {
//            $name = $request->name;
//            $query->where(function ($query) use ($name) {
//                $query->where('name', 'like', '%' . $name . '%')
//                    ->orWhere(function ($q) use ($name) {
//                        $q->whereHas('company', function ($q) use ($name) {
//                            $q->where('name', 'like', '%' . $name . '%');
//                        });
//                    });
//            });
//        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->contract_number . '%');
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user|bd-manager')) {
            $query->whereRaw("(applicant = $user->id or handler = $user->id)");
        } elseif ($user->hasRole('legal-affairs|legal-affairs-manager')) {
            $query->whereRaw("(applicant = $user->id or handler = $user->id or status=3)");
        } elseif ($user->hasRole('purchasing')) {
            //角色为采购时，查询条件为：已审批完成(status=3),product_status为非0（1未出厂or2已出厂）
            $query->whereRaw("(status = 3 and product_status != 0)");
        } else {
            $query->where('status', 3);
        }
        $contract = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($contract, new ContractTransformer())->setStatusCode(200);
    }


    public function store(ContractRequest $request, Contract $contract)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user|bd-manager') && !$user->parent_id) {
            abort(500, '无所属主管，无法新增合同申请');
        }

        $product_status = 0;
        //收款合同且不是服务类型
        if ($request->type == 0 && $request->kind != 4) {
            $product_status = 1;
        }
        $param = $request->all();
        //不是收款合同默认0
        if ($request->type != 0) {
            $param['kind'] = 0;
            $param['special_num'] = 0;
            $param['common_num'] = 0;
        }
        //法务和法务主管建的直接已审批
        if ($user->hasRole('legal-affairs|legal-affairs-manager')) {
            $contract->fill(array_merge($param, ['status' => 3, 'handler' => null, 'product_status' => $product_status]))->save();
        } else {
            $legalId = getProcessStaffId('legal-affairs', 'contract');
            $contract->fill(array_merge($param, ['status' => 1, 'handler' => $legalId, 'product_status' => $product_status]))->save();
        }
        //文档存储
        $ids = explode(',', $request->ids);
        foreach ($ids as $id) {
            $contract->media()->attach($id);
        }

        //收款日期存储
        if ($request->type == 0 && $request->has('receive_date')) {
            $dates = explode(',', $request->receive_date);
            foreach ($dates as $date) {
                ContractReceiveDate::create(['contract_id' => $contract->id, 'receive_date' => $date, 'receive_status' => 0]);
            }
        }

        //硬件存储
        if ($request->kind != 4 && $request->has('product_content')) {
            $param = $request->all();
            $content = $param['product_content'];
            foreach ($content as $item) {
                $item['contract_id'] = $contract->id;
                ContractProduct::query()->create($item);
            }
        }
        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(201);
    }


    public function destroy(Contract $contract)
    {
        if ($contract->status != 1) {
            abort(403, "合同审批状态已更改，不可删除");
        }
        $contract->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }

    public function reject(Request $request, Contract $contract)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        //法务驳回可以修改文件
        if ($user->hasRole('legal-affairs')) {
            $ids = explode(',', $request->ids);
            $contract->media()->detach();
            foreach ($ids as $id) {
                $contract->media()->attach($id);
            }
        }
        $contract->update(array_merge($request->all(), ['status' => 5, 'handler' => $contract->applicant]));
        ContractHistory::updateOrCreate(['user_id' => $user->id, 'contract_id' => $contract->id], ['user_id' => $user->id, 'contract_id' => $contract->id]);

        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(200);
    }

    public function auditing(Request $request, Contract $contract)
    {
        /**@var $user \App\Models\User */
        $user = $this->user();
        abort_if($user->id != $contract->handler, 403, '无审批权限');

        if ($user->hasRole('legal-affairs')) {
            $params = [
                'legal_message',
                'contract_number'
            ];
            $this->checkParam($request, $params);
            $contract->fill(array_merge($request->all(), ['status' => 2, 'handler' => $user->parent_id]));
            $this->updateContractAndHistory($user, $contract);
        } else if ($user->hasRole('legal-affairs-manager')) {
            $params = [
                'legal_ma_message',
                'special_num',
                'common_num'
            ];
            $this->checkParam($request, $params);
            //特批合同需要带合同编号
            if ($contract->status == 4) {
                $this->checkParam($request, ['contract_number']);
            }

            $parentId = $contract->applicantUser->parent_id;
            // BD主管建的直接已审批,不经过自己
            if ($parentId == $contract->applicant) {
                $contract->status = 3;
                $contract->handler = null;
            } else {
                $contract->status = 2;
                $contract->handler = $parentId;
            }

            $contract->fill($request->all());
            $this->updateContractAndHistory($user, $contract);
        } else if ($user->hasRole('bd-manager')) {
            $params = [
                'bd_ma_message'
            ];
            $this->checkParam($request, $params);

            $contract->status = 3;
            $contract->handler = null;
            $contract->bd_ma_message = $request->bd_ma_message;
            $this->updateContractAndHistory($user, $contract);
        }
        return $this->response()->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    private function checkParam(Request $request, array $param)
    {
        if (!$request->has($param)) {
            abort(422, '请填写完整信息');
        }
    }

    private function updateContractAndHistory(User $user, Contract $contract)
    {
        $contract->update();
        $data = [
            'user_id' => $user->id,
            'contract_id' => $contract->id
        ];
        ContractHistory::updateOrCreate($data, $data);
    }

    public function specialAuditing(Request $request, Contract $contract)
    {
        $role = Role::findByName('legal-affairs-manager');
        $legalManager = $role->users()->first();

        if ($contract->status == 2) {
            abort(403, "合同审核中无法申请特批");
        }
        $contract->status = 4;
        $contract->handler = $legalManager->id;
        $contract->update();
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

}
