<?php

namespace App\Http\Controllers\Admin\Payment\V1\Api;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Admin\Payment\V1\Request\PaymentRequest;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Payment $payment)
    {
        return $this->response->item($payment, new PaymentTransformer());
    }

    public function index(PaymentRequest $request, Payment $payment)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = $payment->query();
        if ($request->payee) {
            $query->where('payee', 'like', '%' . $request->payee . '%');
        }
        if ($request->receive_status) {
            $query->where('receive_status', '=', $request->receive_status);
        }
        if ($request->status) {
            $query->where('status', '=', $request->status);
        }
        $payment = $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $this->response->paginator($payment, new PaymentTransformer());
    }

    public function store(PaymentRequest $request, Payment $payment)
    {
        $payment->fill($request->all())->save();
        return $this->response->noContent();
    }

    public function update(PaymentRequest $request,Payment $payment)
    {
        $payment->update($request->all());
        return $this->response->noContent();
    }

}
