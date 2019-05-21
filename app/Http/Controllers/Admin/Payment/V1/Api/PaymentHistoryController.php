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
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentTransformer;

class PaymentHistoryController extends Controller
{
    public function index(Request $request, Payment $payment): Response
    {
        $query = $payment->query();

        if ($request->get('start_date') && $request->get('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }
        if ($request->get('applicant')) {
            $query->where('applicant', '=', $request->get('applicant'));
        }
        if ($request->get('payee')) {
            $query->where('payee', 'like', '%' . $request->get('payee') . '%');
        }
        if ($request->get('receive_status') !== null) {
            $query->where('receive_status', '=', $request->get('receive_status'));
        }
        if ($request->get('status') !== null) {
            $query->where('status', '=', $request->get('status'));
        }
        if ($request->get('contract_number') !== null) {
            $query->whereHas('contract', static function ($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
            });
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        $query->whereHas('paymentHistory', static function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $payments = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($payments, new PaymentTransformer());
    }


    public function export(Request $request)
    {
        return excelExportByType($request,'payment_history');
    }
}