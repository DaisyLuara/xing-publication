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
use App\Models\User;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class PaymentPayeeController extends Controller
{
    public function show(PaymentPayee $paymentPayee): Response
    {
        return $this->response()->item($paymentPayee, new PaymentPayeeTransformer());
    }

    public function index(Request $request, PaymentPayee $paymentPayee): Response
    {
        $query = $paymentPayee->query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        /** @var User $user */
        $user = $this->user();
        if ($user->hasRole('user|bd-manager')) {
            $query->where('owner', $user->id);
        }
        $paymentPayee = $query->orderByDesc('created_at')->paginate(10);

        return $this->response()->paginator($paymentPayee, new PaymentPayeeTransformer());
    }

    public function store(PaymentPayeeRequest $request, PaymentPayee $paymentPayee): Response
    {
        $user = $this->user();
        $paymentPayee->fill(array_merge($request->all(), ['user_id' => $user->id, 'owner' => $user->id]))->save();
        return $this->response()->noContent();
    }

    public function update(PaymentPayeeRequest $request, PaymentPayee $paymentPayee): Response
    {
        $paymentPayee->update($request->all());
        return $this->response()->noContent();
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'payment_payee');
    }
}