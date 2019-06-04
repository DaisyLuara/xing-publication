<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 下午3:14
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Api;


use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceTransformer;

class InvoiceHistoryController extends Controller
{
    public function index(Request $request, Invoice $invoice)
    {
        $query = $invoice->query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ");
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

        $query->whereHas('invoiceHistory', static function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $invoice = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($invoice, new InvoiceTransformer())->setStatusCode(200);
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'invoice_history');
    }
}