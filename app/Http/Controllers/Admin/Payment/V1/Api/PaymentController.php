<?php

namespace App\Http\Controllers\Admin\Payment\V1\Api;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Admin\Payment\V1\Request\PaymentRequest;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        if ($user->hasPermissionTo('finance_pay')) {
            $query->whereRaw("(handler=$user->id or status=4)");
        } else if ($user->hasRole('operation')) {
            $query->whereRaw("(status=3 or status=4)");
        } else {
            $query->whereRaw("(applicant=$user->id or handler=$user->id)");
        }
        $payment = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($payment, new PaymentTransformer());
    }

    public function store(PaymentRequest $request, Payment $payment)
    {
        $user = $this->user();
        if (!$user->parent_id) {
            abort(500, '无所属主管，无法新增付款申请');
        }
        $payment->fill(array_merge($request->all(), ['status' => 1, 'handler' => $user->parent_id, 'receive_status' => 0]))->save();
        return $this->response->noContent();
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($payment->status == 5) {
            $payment->update(array_merge($request->all(), ['status' => 1, 'handler' => $user->parent_id]));
        } else {
            $payment->update(array_merge($request->all(), ['status' => 5, 'handler' => $payment->applicant]));
        }

        return $this->response->item($payment, new PaymentTransformer());
    }

    public function destroy(Payment $payment)
    {
        if ($payment->status != 1) {
            abort(500, "合同审批状态已更改，不可删除");
        }
        $payment->delete();
        return $this->response->noContent();
    }

    public function auditing(Request $request, Payment $payment)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if ($user->hasRole('bd-manager')) {
            $role = Role::findByName('legal-affairs');
            $legals = $role->users()->get();
            foreach ($legals as $legal) {
                if ($legal->hasPermissionTo('auditing')) {
                    $payment->status = 2;
                    $payment->handler = $legal->id;
                    $payment->update();
                }
            }
        } else if ($user->hasRole('legal-affairs')) {

            $payment->handler = $user->parent_id;
            $payment->update();

        } else if ($user->hasRole('legal-affairs-manager')) {

            $role = Role::findByName('auditor');
            $auditors = $role->users()->get();
            foreach ($auditors as $auditor) {
                if ($auditor->hasPermissionTo('auditing')) {
                    $payment->handler = $auditor->id;
                    $payment->update();
                }
            }
        } else if ($user->hasRole('auditor')) {

            $permission = Permission::findByName('finance_pay');
            $finance = $permission->users()->first();
            $payment->status = 3;
            $payment->handler = $finance->id;
            $payment->update();

        } else if ($user->hasRole('finance')) {
            $payment->status = 4;
            $payment->handler = null;
            $payment->update();
        }
        return $this->response->noContent();
    }

    public function receive(Request $request, Payment $payment)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasPermissionTo('finance_pay')) {
            abort(500, "无操作权限");
        }
        $payment->receive_status = 1;
        $payment->update();
        return $this->response->noContent();
    }
}
