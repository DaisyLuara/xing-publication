<?php

namespace App\Http\Controllers\Admin\Payment\V1\Api;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Admin\Payment\V1\Request\PaymentRequest;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory;

class PaymentController extends Controller
{
    public function show(Payment $payment): Response
    {
        return $this->response->item($payment, new PaymentTransformer());
    }

    public function index(PaymentRequest $request, Payment $payment): Response
    {

        $query = $payment->query();

        if ($request->get('start_date') && $request->get('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }
        if ($request->get('payee')) {
            $query->where('payee', 'like', '%' . $request->get('payee') . '%');
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
        if ($request->has('contract_number')) {
            $query->whereHas('contract', static function ($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
            });
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->id === getProcessStaffId('finance', 'payment')) {
            $query->whereRaw("(handler=$user->id or status=4)");
        } else if ($user->hasRole('operation')) {
            $query->whereRaw('(status=3 or status=4)');
        } else {
            $query->whereRaw("(applicant=$user->id or handler=$user->id)");
        }
        $payments = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($payments, new PaymentTransformer());
    }

    public function store(PaymentRequest $request, Payment $payment): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->parent_id && ($user->hasRole('user') || $user->hasRole('bd-manager')) ) {
            abort(500, '无所属主管，无法新增付款申请');
        }
        if ($user->hasRole('legal-affairs')) {
            $financeId = getProcessStaffId('finance', 'payment');
            $payment->fill(array_merge($request->all(), ['status' => 3, 'handler' => $financeId, 'receive_status' => 0]))->save();
        }

        if ($user->hasRole('legal-affairs-manager')) {
            $financeId = getProcessStaffId('finance', 'payment');
            $payment->fill(array_merge($request->all(), ['status' => 3, 'handler' => $financeId, 'receive_status' => 0]))->save();
        }


        if ($user->hasRole('user')) {
            $payment->fill(array_merge($request->all(), ['status' => 1, 'handler' => $user->parent_id, 'receive_status' => 0]))->save();
        }

        if ($user->hasRole('bd-manager')) {
            $legalId = getProcessStaffId('legal-affairs', 'payment');
            $payment->fill(array_merge($request->all(), ['status' => 1, 'handler' => $legalId, 'receive_status' => 0]))->save();
        }
        //附件存储
        if ($request->get('ids')) {
            $ids = explode(',', $request->get('ids'));
            foreach ($ids as $id) {
                $payment->media()->attach($id);
            }
        }

        activity('create_payment')
            ->causedBy($user)
            ->performedOn($payment)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增付款申请');

        return $this->response()->noContent();
    }


    /**
     * @param Payment $payment
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function destroy(Payment $payment, Request $request): Response
    {
        if ($payment->status !== 1) {
            abort(403, '合同审批状态已更改，不可删除');
        }
        $payment->delete();

        activity('delete_payment')
            ->causedBy($this->user())
            ->performedOn($payment)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => []])
            ->log('删除付款申请');

        return $this->response->noContent();
    }

    public function reject(Request $request, Payment $payment): Response
    {
        $user = $this->user();
        $payment->update(array_merge($request->all(), ['status' => 5, 'handler' => $payment->applicant]));
        PaymentHistory::query()->updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);

        activity('reject_payment')
            ->causedBy($user)
            ->performedOn($payment)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('驳回付款申请');

        return $this->response()->noContent();
    }

    public function auditing(Request $request, Payment $payment): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if ($user->hasRole('bd-manager')) {
            $legalId = getProcessStaffId('legal-affairs', 'payment');
            $payment->status = 2;
            $payment->handler = $legalId;
            if (!$request->has('bd_ma_message')) {
                abort(500, '没有填写意见');
            }
            $payment->bd_ma_message = $request->get('bd_ma_message');
            $payment->update();
            PaymentHistory::query()->updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);

        } else if ($user->hasRole('legal-affairs')) {
            $payment->handler = $user->parent_id;
            if (!$request->has('legal_message')) {
                abort(500, '没有填写意见');
            }
            $payment->legal_message = $request->get('legal_message');
            $payment->update();
            PaymentHistory::query()->updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);
        } else if ($user->hasRole('legal-affairs-manager')) {

            $auditorId = getProcessStaffId('auditor', 'payment');
            $payment->handler = $auditorId;
            $payment->legal_ma_message = $request->get('legal_ma_message');
            if (!$request->has('legal_ma_message')) {
                abort(500, '没有填写意见');
            }
            $payment->update();
            PaymentHistory::query()->updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);

        } else if ($user->hasRole('auditor')) {
            $financeId = getProcessStaffId('finance', 'payment');
            $payment->status = 3;
            $payment->handler = $financeId;
            if (!$request->has('auditor_message')) {
                abort(500, '没有填写意见');
            }
            $payment->auditor_message = $request->get('auditor_message');
            $payment->update();
            PaymentHistory::query()->updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);

        } else if ($user->hasRole('finance')) {
            $payment->status = 4;
            $payment->handler = null;
            $payment->payer = $user->name;
            $payment->update();
            PaymentHistory::query()->updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);
        }

        activity('audit_payment')
            ->causedBy($user)
            ->performedOn($payment)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('审计付款申请');

        return $this->response->noContent();
    }

    public function receive(Request $request, Payment $payment): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->id !== getProcessStaffId('finance', 'payment')) {
            abort(403, '无操作权限');
        }
        $payment->receive_status = 1;
        $payment->update();

        activity('receive_payment')
            ->causedBy($user)
            ->performedOn($payment)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('确定处理付款申请');

        return $this->response->noContent();
    }


    public function export(Request $request)
    {
        return excelExportByType($request,'payment');
    }
}
