<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 下午2:41
 */

namespace App\Http\Controllers\Admin\Payment\V1\Api;


use App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee;
use App\Http\Controllers\Admin\Payment\V1\Request\PaymentPayeeRequest;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentPayeeTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentPayeeController extends Controller
{
    public function show(PaymentPayee $paymentPayee)
    {
        return $this->response()->item($paymentPayee, new PaymentPayeeTransformer());
    }

    public function index(Request $request, PaymentPayee $paymentPayee)
    {
        $query = $paymentPayee->query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('user_id', $user->id);
        }
        $paymentPayee = $query->orderByDesc('created_at')->paginate(10);

        return $this->response()->paginator($paymentPayee, new PaymentPayeeTransformer());
    }

    public function store(PaymentPayeeRequest $request, PaymentPayee $paymentPayee)
    {
        $user = $this->user();
        $paymentPayee->fill(array_merge($request->all(), ['user_id' => $user->id]))->save();

        activity('create_payment_payee')
            ->causedBy($user)
            ->performedOn($paymentPayee)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增收款人');

        return $this->response()->noContent();
    }

    public function update(PaymentPayeeRequest $request, PaymentPayee $paymentPayee)
    {
        $paymentPayee->update($request->all());

        activity('update_payment_payee')
            ->causedBy($this->user())
            ->performedOn($paymentPayee)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑收款人');

        return $this->response()->noContent();
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'payment_payee');
    }
}