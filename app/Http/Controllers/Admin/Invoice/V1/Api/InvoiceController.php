<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Api;

use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory;
use App\Http\Controllers\Admin\Invoice\V1\Request\InvoiceRequest;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        return $this->response()->item($invoice, new InvoiceTransformer())->setStatusCode(200);
    }

    public function index(InvoiceRequest $request, Invoice $invoice)
    {

        $query = $invoice->query();

        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ");
        }

        if ($request->name) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->whereHas('company', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                });
            });
        }

        if ($request->status) {
            $query->where('status', '=', $request->status);
        }

        if ($request->has('contract_number')) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->contract_number . '%');
            });
        }

        if ($request->has('receive_status')) {
            $query->where('receive_status', '=', $request->receive_status);
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasPermissionTo('finance_bill')) {
            $query->whereRaw("(handler=$user->id or status=4 or status=5)");
        } else if ($user->hasRole('operation')) {
            $query->whereRaw('(status=3 or status=4 or status=5)');
        } else {
            $query->whereRaw("(applicant=$user->id or handler=$user->id)");
        }
        $invoice = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($invoice, new InvoiceTransformer())->setStatusCode(200);
    }

    public function store(InvoiceRequest $request, Invoice $invoice)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (($user->hasRole('user') || $user->hasRole('bd-manager')) && !$user->parent_id) {
            abort(500, '无所属主管，无法新增开票申请');
        }
        $invoice = $request->all();
        $content = $invoice['invoice_content'];
        unset($invoice['invoice_content']);
        if ($user->hasRole('legal-affairs')) {
            $invoice = Invoice::query()->create(array_merge($invoice, ['status' => 2, 'handler' => $user->parent_id]));
        }
        if ($user->hasRole('legal-affairs-manager')) {
            $permission = Permission::findByName('finance_bill');
            $finance = $permission->users()->first();
            $invoice = Invoice::query()->create(array_merge($invoice, ['status' => 3, 'handler' => $finance->id]));
        }
        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $invoice = Invoice::query()->create(array_merge($invoice, ['status' => 1, 'handler' => $user->parent_id]));
        }
        foreach ($content as $item) {
            $item['invoice_id'] = $invoice['id'];
            InvoiceContent::query()->create($item);
        }
        return $this->response()->item($invoice, new InvoiceTransformer())->setStatusCode(201);
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('user') && !$user->hasRole('bd-manager')) {
            abort(403, '无操作权限');
        }
        $invoice->update(array_merge($request->all(), ['handler' => $user->parent_id, 'status' => 1]));
        $content = $request->invoice_content;
        InvoiceContent::query()
            ->where('invoice_id', '=', $invoice['id'])
            ->delete();
        foreach ($content as $item) {
            $item['invoice_id'] = $invoice['id'];
            InvoiceContent::query()->create($item);
        }

        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status != 1) {
            abort(403, "合同审批状态已更改，不可删除");
        }
//        InvoiceContent::query()
//            ->where('invoice_id', '=', $invoice['id'])
//            ->delete();
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
            $invoice->bd_ma_message = $request->bd_ma_message;
            $invoice->update();
            InvoiceHistory::updateOrCreate(['user_id' => $user->id, 'invoice_id' => $invoice->id], ['user_id' => $user->id, 'invoice_id' => $invoice->id]);

        } else if ($user->hasRole('legal-affairs-manager')) {
            $permission = Permission::findByName('finance_bill');
            $finance = $permission->users()->first();
            $invoice->status = 3;
            $invoice->handler = $finance->id;
            if (!$request->has('legal_ma_message')) {
                abort(500, '没有填写意见');
            }
            $invoice->legal_ma_message = $request->legal_ma_message;
            $invoice->update();
            InvoiceHistory::updateOrCreate(['user_id' => $user->id, 'invoice_id' => $invoice->id], ['user_id' => $user->id, 'invoice_id' => $invoice->id]);
        } else if ($user->hasRole('finance')) {
            $invoice->status = 4;
            $invoice->handler = $invoice->applicant;
            $invoice->update();
            InvoiceHistory::updateOrCreate(['user_id' => $user->id, 'invoice_id' => $invoice->id], ['user_id' => $user->id, 'invoice_id' => $invoice->id]);
        }

        return $this->response()->item($invoice, new InvoiceTransformer())->setStatusCode(201);
    }

    public function receive(Invoice $invoice)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('finance')) {
            abort(403, '无操作权限');
        }
        if ($invoice->status != 4) {
            abort(403, "不能领取票据");
        }
        $invoice->status = 5;
        $invoice->handler = null;
        $invoice->update();
        return $this->response()->noContent();
    }

//    public function receipt(Invoice $invoice)
//    {
//        /** @var  $user \App\Models\User */
//        $user = $this->user();
//        if (!$user->hasPermissionTo('finance_bill')) {
//            abort(403, "无操作权限");
//        }
//        $invoice->receive_status = 1;
//        $invoice->update();
//        return $this->response()->noContent();
//
//    }
}