<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent;
use App\Http\Controllers\Admin\Invoice\V1\Request\InvoiceRequest;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        return $this->response->item($invoice, new InvoiceTransformer());
    }

    public function index(InvoiceRequest $request, Invoice $invoice)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = $invoice->query();
        if ($request->name) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->status) {
            $query->where('status', '=', $request->status);
        }

        $invoice = $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate'")
            ->orderBy('created_at', 'desc')
            ->paginate(10);;
        return $this->response->paginator($invoice, new InvoiceTransformer());
    }

    public function store(InvoiceRequest $request, Invoice $invoice)
    {
        $invoice = $request->all();
        $content = $invoice['invoice_content'];
        unset($invoice['invoice_content']);
        $invoice = Invoice::query()->create($invoice);
        foreach ($content as $item) {
            $item['invoice_id'] = $invoice['id'];
            InvoiceContent::query()->create($item);
        }
        return $this->response->noContent();
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        $invoice = $request->all();
        $content = $invoice['invoice_content'];
        unset($invoice['invoice_content']);

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
}
