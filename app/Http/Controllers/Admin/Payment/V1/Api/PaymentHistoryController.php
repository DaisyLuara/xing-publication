<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 下午3:31
 */

namespace App\Http\Controllers\Admin\Payment\V1\Api;


use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentTransformer;

class PaymentHistoryController extends Controller
{
    public function index(Request $request, Payment $payment)
    {
        $query = $payment->query();

        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ");
        }

        if ($request->payee) {
            $query->where('payee', 'like', '%' . $request->payee . '%');
        }
        if ($request->has('receive_status')) {
            $query->where('receive_status', '=', $request->receive_status);
        }
        if ($request->status) {
            $query->where('status', '=', $request->status);
        }
        if ($request->has('contract_number')) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->contract_number . '%');
            });
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        $query->whereHas('paymentHistory', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $payment = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($payment, new PaymentTransformer());
    }


    public function export(Request $request)
    {
        return excelExportByType($request,'payment_history');
    }
}