<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Api;

use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory;
use App\Http\Controllers\Admin\Invoice\V1\Request\InvoiceRequest;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DB;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        return $this->response()->item($invoice, new InvoiceTransformer())->setStatusCode(200);
    }

    public function index(InvoiceRequest $request, Invoice $invoice)
    {

        $query = $invoice->query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }

        if ($request->filled('name')) {
            $query->whereHas('contract', static function ($q) use ($request) {
                $q->whereHas('company', static function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->get('name') . '%');
                });
            });
        }

        if ($request->filled('owner')) {
            $query->where('owner', '=', $request->get('owner'));
        }

        if ($request->filled('status')) {
            $query->where('status', '=', $request->get('status'));
        }

        if ($request->filled('contract_number')) {
            $query->whereHas('contract', static function ($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
            });
        }

        if ($request->filled('receive_status')) {
            $query->where('receive_status', '=', $request->get('receive_status'));
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->id === getProcessStaffId('finance', 'invoice')) {
            $query->whereRaw("(handler=$user->id or status=4 or status=5)");
        } else if ($user->hasRole('operation')) {
            $query->whereRaw('(status=3 or status=4 or status=5)');
        } else {
            $query->whereRaw("(owner=$user->id or handler=$user->id)");
        }
        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($invoices, new InvoiceTransformer())->setStatusCode(200);
    }

    public function store(InvoiceRequest $request, Invoice $invoice)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->parent_id && $user->hasRole('user|bd-manager')) {
            abort(500, '无所属主管，无法新增开票申请');
        }
        $param = $request->all();
        $content = $param['invoice_content'];
        unset($param['invoice_content']);

        $rest = [];
        if ($user->hasRole('legal-affairs|legal-affairs-manager')) {
            $financeId = getProcessStaffId('finance', 'invoice');
            $rest = ['status' => 3, 'handler' => $financeId, 'owner' => $user->id];
        }
        if ($user->hasRole('user')) {
            $rest = ['status' => 1, 'handler' => $user->parent_id, 'owner' => $user->id];
        }
        if ($user->hasRole('bd-manager')) {
            $role = Role::findByName('legal-affairs-manager');
            $legalMa = $role->users()->first();
            $rest = ['status' => 1, 'handler' => $legalMa->id, 'owner' => $user->id];
        }
        try {
            DB::beginTransaction();
            $invoice = Invoice::query()->create(array_merge($param, $rest));
            foreach ($content as $item) {
                $item['invoice_id'] = $invoice['id'];
                InvoiceContent::query()->create($item);
            }
            //文件存储
            if ($request->filled('ids')) {
                $ids = explode(',', $request->get('ids'));
                foreach ($ids as $id) {
                    $invoice->media()->attach($id);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            abort(500, '系统错误');
        }

        return $this->response()->item($invoice, new InvoiceTransformer())->setStatusCode(201);
    }


    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 1) {
            abort(403, '合同审批状态已更改，不可删除');
        }
        $invoice->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }

    public function reject(Request $request, Invoice $invoice)
    {

        /** @var  $user \App\Models\User */
        $user = $this->user();
        $invoice->update(array_merge($request->all(), ['handler' => $invoice->applicant, 'status' => 6]));
        InvoiceHistory::updateOrCreate(['user_id' => $user->id, 'invoice_id' => $invoice->id], ['user_id' => $user->id, 'invoice_id' => $invoice->id]);
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function auditing(Request $request, Invoice $invoice)
    {
        /**@var $user \App\Models\User */
        $user = $this->user();

        if ($user->hasRole('bd-manager')) {
            $role = Role::findByName('legal-affairs-manager');
            $legalMa = $role->users()->first();

            $invoice->status = 2;
            $invoice->handler = $legalMa->id;
            if (!$request->has('bd_ma_message')) {
                abort(500, '没有填写意见');
            }
            $invoice->bd_ma_message = $request->get('bd_ma_message');
            $invoice->update();
            InvoiceHistory::updateOrCreate(['user_id' => $user->id, 'invoice_id' => $invoice->id], ['user_id' => $user->id, 'invoice_id' => $invoice->id]);

        } else if ($user->hasRole('legal-affairs-manager')) {
            $financeId = getProcessStaffId('finance', 'invoice');
            $invoice->status = 3;
            $invoice->handler = $financeId;
            if (!$request->has('legal_ma_message')) {
                abort(500, '没有填写意见');
            }
            $invoice->legal_ma_message = $request->get('legal_ma_message');
            $invoice->update();
            InvoiceHistory::updateOrCreate(['user_id' => $user->id, 'invoice_id' => $invoice->id], ['user_id' => $user->id, 'invoice_id' => $invoice->id]);
        } else if ($user->hasRole('finance')) {
            $invoice->status = 4;
            $invoice->handler = $user->id;
            $invoice->drawer = $user->name;
            $invoice->update();
            InvoiceHistory::updateOrCreate(['user_id' => $user->id, 'invoice_id' => $invoice->id], ['user_id' => $user->id, 'invoice_id' => $invoice->id]);
        }

        return $this->response()->item($invoice, new InvoiceTransformer())->setStatusCode(201);
    }

    public function receive(Invoice $invoice): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('finance')) {
            abort(403, '无操作权限');
        }
        if ($invoice->status !== 4) {
            abort(403, '不能领取票据');
        }
        $invoice->status = 5;
        $invoice->handler = null;
        $invoice->update();
        return $this->response()->noContent();
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'invoice');
    }

}