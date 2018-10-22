<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent;
use App\Http\Controllers\Admin\Invoice\V1\Request\InvoiceRequest;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceTransformer;
use App\Http\Controllers\Controller;
use App\Models\User;
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
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->status) {
            $query->where('status', '=', $request->status);
        }

        $invoice = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($invoice, new InvoiceTransformer());
    }

    public function store(InvoiceRequest $request, Invoice $invoice)
    {
        $invoice = $request->all();
        $content = $invoice['invoice_content'];
        unset($invoice['invoice_content']);
        $invoice = Invoice::query()->create(array_merge($invoice, ['status' => 1, 'handler' => $this->user()->parent_id]));
        foreach ($content as $item) {
            $item['invoice_id'] = $invoice['id'];
            InvoiceContent::query()->create($item);
        }
        return $this->response->noContent();
    }

    public function update(InvoiceRequest $request)
    {

        $invoice = $request->all();
        $content = $invoice['invoice_content'];
        unset($invoice['invoice_content']);

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $invoice['handler'] = $user->parent_id;
        } else {
            $invoice['handler'] = $invoice['applicant'];
        }
        Invoice::query()->update($invoice);
        InvoiceContent::query()
            ->where('invoice_id', '=', $invoice['id'])
            ->delete();
        foreach ($content as $item) {
            $item['invoice_id'] = $invoice['id'];
            InvoiceContent::query()->create($item);
        }
        return $this->response->noContent();
    }

    public function destroy(Invoice $invoice)
    {
        InvoiceContent::query()
            ->where('invoice_id', '=', $invoice['id'])
            ->delete();
        $invoice->delete();
        return $this->response->noContent();
    }

    public function auditing(Invoice $invoice)
    {

        /**@var $user \App\Models\User */
        $user = $this->user();
        $data = $user->getRoleNames();
        switch ($data[0]) {
            case 'bd-manager':
                $role = Role::findByName('legal-affairs');
                $legal = $role->users()->first();
                $invoice->status = 2;
                $invoice->handler = $legal->id;
                $invoice->update();
                break;
            case 'legal-affairs':
                $invoice->handler = $user->parent_id;
                $invoice->update();
                break;
            case 'legal-affairs-manager' :
                $permission=Permission::findByName('finance_bill');
                $finance=$permission->users()->first();
                $invoice->status = 3;
                $invoice->handler =$finance->id;
                $invoice->update();
                break;
            case 'finance' :
                $invoice->status=4;
                $invoice->handler=$invoice->applicant;
                $invoice->update();
                break;
            default:
                break;
        }
    }

    public function receive(Invoice $invoice){
        $invoice->status=5;
        $invoice->handler=null;
        $invoice->update();
        return $this->response->noContent();
    }

}
