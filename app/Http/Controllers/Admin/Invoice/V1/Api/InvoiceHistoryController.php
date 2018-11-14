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

        $query->whereHas('invoiceHistory', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $invoice = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($invoice, new InvoiceTransformer())->setStatusCode(200);
    }
}