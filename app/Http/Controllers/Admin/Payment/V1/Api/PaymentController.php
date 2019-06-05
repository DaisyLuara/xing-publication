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
        return $this->response()->item($payment, new PaymentTransformer());
    }

    public function index(PaymentRequest $request, Payment $payment): Response
    {

        $query = $payment->query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }

        if ($request->filled('owner')) {
            $query->where('owner', '=', $request->get('owner'));
        }

        if ($request->filled('payment_payee_name')) {
            $query->whereHas('paymentPayee', static function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('payment_payee_name') . '%');
            });
        }

        if ($request->filled('receive_status')) {
            $query->where('receive_status', '=', $request->get('receive_status'));
        }

        if ($request->filled('status')) {
            $query->where('status', '=', $request->get('status'));
        }

        if ($request->filled('contract_number')) {
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
            $query->whereRaw("(owner=$user->id or handler=$user->id)");
        }
        $payments = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($payments, new PaymentTransformer());
    }

    public function store(PaymentRequest $request, Payment $payment): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->parent_id || $user->hasRole('use|bd-manager')) {
            abort(500, '无所属主管，无法新增付款申请');
        }
        $rest = [];
        if ($user->hasRole('legal-affairs|legal-affairs-manager')) {
            $financeId = getProcessStaffId('finance', 'payment');
            $rest = ['status' => 3, 'handler' => $financeId, 'receive_status' => 0, 'owner' => $user->id, 'applicant' => $user->id];
        }
        if ($user->hasRole('user')) {
            $rest = ['status' => 1, 'handler' => $user->parent_id, 'receive_status' => 0, 'owner' => $user->id, 'applicant' => $user->id];
        }

        if ($user->hasRole('bd-manager')) {
            $legalId = getProcessStaffId('legal-affairs', 'payment');
            $rest = ['status' => 1, 'handler' => $legalId, 'receive_status' => 0, 'owner' => $user->id, 'applicant' => $user->id];
        }
        $payment->fill(array_merge($request->all(), $rest))->save();
        //附件存储
        if ($request->get('ids')) {
            $ids = explode(',', $request->get('ids'));
            foreach ($ids as $id) {
                $payment->media()->attach($id);
            }
        }
        return $this->response()->noContent();
    }


    public function destroy(Payment $payment): Response
    {
        if ($payment->status !== 1) {
            abort(403, '合同审批状态已更改，不可删除');
        }
        $payment->delete();
        return $this->response()->noContent();
    }

    public function reject(Request $request, Payment $payment): Response
    {
        $user = $this->user();
        $payment->update(array_merge($request->all(), ['status' => 5, 'handler' => $payment->applicant]));
        PaymentHistory::updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);
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
            PaymentHistory::updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);

        } else if ($user->hasRole('legal-affairs')) {
            $payment->handler = $user->parent_id;
            if (!$request->has('legal_message')) {
                abort(500, '没有填写意见');
            }
            $payment->legal_message = $request->get('legal_message');
            $payment->update();
            PaymentHistory::updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);
        } else if ($user->hasRole('legal-affairs-manager')) {

            $auditorId = getProcessStaffId('auditor', 'payment');
            $payment->handler = $auditorId;
            $payment->legal_ma_message = $request->get('legal_ma_message');
            if (!$request->has('legal_ma_message')) {
                abort(500, '没有填写意见');
            }
            $payment->update();
            PaymentHistory::updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);

        } else if ($user->hasRole('auditor')) {
            $financeId = getProcessStaffId('finance', 'payment');
            $payment->status = 3;
            $payment->handler = $financeId;
            if (!$request->has('auditor_message')) {
                abort(500, '没有填写意见');
            }
            $payment->auditor_message = $request->get('auditor_message');
            $payment->update();
            PaymentHistory::updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);

        } else if ($user->hasRole('finance')) {
            $payment->status = 4;
            $payment->handler = null;
            $payment->payer = $user->name;
            $payment->update();
            PaymentHistory::updateOrCreate(['user_id' => $user->id, 'payment_id' => $payment->id], ['user_id' => $user->id, 'payment_id' => $payment->id]);
        }
        return $this->response()->noContent();
    }

    public function receive(Payment $payment): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->id !== getProcessStaffId('finance', 'payment')) {
            abort(403, '无操作权限');
        }
        $payment->receive_status = 1;
        $payment->update();
        return $this->response()->noContent();
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'payment');
    }
}
