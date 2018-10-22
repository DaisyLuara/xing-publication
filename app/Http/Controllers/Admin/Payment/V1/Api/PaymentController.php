<?php

namespace App\Http\Controllers\Admin\Payment\V1\Api;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Admin\Payment\V1\Request\PaymentRequest;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentTransformer;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PaymentController extends Controller
{
    public function show(Payment $payment)
    {
        return $this->response->item($payment, new PaymentTransformer());
    }

    public function index(PaymentRequest $request, Payment $payment)
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
        if ($request->receive_status) {
            $query->where('receive_status', '=', $request->receive_status);
        }
        if ($request->status) {
            $query->where('status', '=', $request->status);
        }
        $payment = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($payment, new PaymentTransformer());
    }

    public function store(PaymentRequest $request, Payment $payment)
    {
        $payment->fill(array_merge($request->all(), ['status' => 1, 'handler' => $this->user()->parent_id, 'receive_status' => 0]))->save();
        return $this->response->noContent();
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $payment->update($request->all());
        return $this->response->noContent();
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return $this->response->noContent();
    }

    public function auditing(Payment $payment)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $data = $user->getRoleNames();
        switch ($data[0]) {
            case 'bd-manager':
                $role = Role::findByName('legal-affairs');
                $legal = $role->users()->first();
                $payment->status = 2;
                $payment->handler = $legal->id;
                $payment->update();
                break;
            case 'legal-affairs':
                $payment->handler = $user->parent_id;
                $payment->update();
                break;
            case 'legal-affairs-manager':
                $role = Role::findByName('auditor');
                $auditor = $role->users()->first();
                $payment->handler = $auditor->id;
                $payment->update();
                break;
            case 'auditor':
                $permission = Permission::findByName('finance_pay');
                $finance = $permission->users()->first();
                $payment->status = 3;
                $payment->handler = $finance->id;
                $payment->update();
                break;
            case 'finance':
                $payment->status = 4;
                $payment->handler = null;
                $payment->update();
                break;
            default:
                break;
        }
        return $this->response->noContent();
    }

    public function receive(Payment $payment)
    {
        $payment->receive_status = 1;
        $payment->update();
        return $this->response->noContent();
    }
}
