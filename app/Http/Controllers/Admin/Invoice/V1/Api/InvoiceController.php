<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Api;

use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent;
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
        return $this->response->item($invoice, new InvoiceTransformer());
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
        } else {
            $query->whereRaw("(applicant=$user->id or handler=$user->id)");
        }
        $invoice = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($invoice, new InvoiceTransformer());
    }

    public function store(InvoiceRequest $request, Invoice $invoice)
    {
        $user = $this->user();
        if (!$user->parent_id) {
            abort(500, '无所属主管，无法新增开票申请');
        }
        $invoice = $request->all();
        $content = $invoice['invoice_content'];
        unset($invoice['invoice_content']);
        $invoice = Invoice::query()->create(array_merge($invoice, ['status' => 1, 'handler' => $user->parent_id]));
        foreach ($content as $item) {
            $item['invoice_id'] = $invoice['id'];
            InvoiceContent::query()->create($item);
        }
        return $this->response->noContent();
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($invoice->status == 6) {
            $invoice->update(array_merge($request->all(), ['handler' => $user->parent_id, 'status' => 1]));
            $content = $request->invoice_content;
            InvoiceContent::query()
                ->where('invoice_id', '=', $invoice['id'])
                ->delete();
            foreach ($content as $item) {
                $item['invoice_id'] = $invoice['id'];
                InvoiceContent::query()->create($item);
            }
        } else {
            $invoice->update(array_merge($request->all(), ['handler' => $invoice->applicant, 'status' => 6]));
        }
        return $this->response->noContent();
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status != 1) {
            abort(500, "合同审批状态已更改，不可删除");
        }
        InvoiceContent::query()
            ->where('invoice_id', '=', $invoice['id'])
            ->delete();
        $invoice->delete();
        return $this->response->noContent();
    }

    public function auditing(Request $request, Invoice $invoice)
    {
        /**@var $user \App\Models\User */
        $user = $this->user();

        if ($user->hasRole('bd-manager')) {
            $role = Role::findByName('legal-affairs');
            $legals = $role->users()->get();
            foreach ($legals as $legal) {
                if ($legal->hasPermissionTo('auditing')) {
                    $invoice->status = 2;
                    $invoice->handler = $legal->id;
                    $invoice->update();
                }
            }
        } else if ($user->hasRole('legal-affairs')) {
            $invoice->handler = $user->parent_id;
            $invoice->update();
        } else if ($user->hasRole('legal-affairs-manager')) {
            $permission = Permission::findByName('finance_bill');
            $finance = $permission->users()->first();
            $invoice->status = 3;
            $invoice->handler = $finance->id;
            $invoice->update();
        } else if ($user->hasRole('finance')) {
            $invoice->status = 4;
            $invoice->handler = $invoice->applicant;
            $invoice->update();
        }

        return $this->response->item($invoice, new InvoiceTransformer())->setStatusCode(201);
    }

    public function receive(Invoice $invoice)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($invoice->status != 4) {
            abort(500, "不能领取票据");
        }
        $invoice->status = 5;
        $invoice->handler = null;
        $invoice->update();
        return $this->response->noContent();
    }

    public function receipt(Invoice $invoice)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasPermissionTo('finance_bill')) {
            abort(500, "无操作权限");
        }
        $invoice->receive_status = 1;
        $invoice->update();
        return $this->response->noContent();

    }
}