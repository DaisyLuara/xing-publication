<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/12
 * Time: 下午2:13
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use App\Http\Controllers\Admin\Invoice\V1\Request\InvoiceReceiptRequest;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceReceiptTransformer;
use App\Http\Controllers\Controller;
use App\Jobs\InvoiceReceiptNotificationJob;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceReceiptController extends Controller
{
    public function show(InvoiceReceipt $invoiceReceipt)
    {
        return $this->response()->item($invoiceReceipt, new InvoiceReceiptTransformer());
    }

    public function index(Request $request, InvoiceReceipt $invoiceReceipt)
    {
        $query = $invoiceReceipt->query();
        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ");
        }

        if ($request->has('name')) {
            $query->where('receipt_company', 'like', '%' . $request->name . '%');
        }

        if ($request->has('claim_status')) {
            $query->where('claim_status', $request->claim_status);
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user')) {
            $query->whereHas('receiveDate', function ($q) use ($user) {
                $q->whereHas('contract', function ($q) use ($user) {
                    $q->where('applicant', $user->id);
                });
            });
        }

        if ($user->hasRole('bd-manager')) {
            $query->whereHas('receiveDate', function ($q) use ($user) {
                $q->whereHas('contract', function ($q) use ($user) {
                    $q->whereHas('applicantUser', function ($q) use ($user) {
                        $q->where('parent_id', $user->id);
                    });
                });
            });
        }

        $invoiceReceipt = $query->orderByDesc('id')->paginate(10);;

        return $this->response()->paginator($invoiceReceipt, new InvoiceReceiptTransformer());
    }

    public function store(InvoiceReceiptRequest $request, InvoiceReceipt $invoiceReceipt)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('finance')) {
            abort(403, '无操作权限');
        }

        $invoiceReceipt->fill(array_merge($request->all(), ['claim_status' => 0, 'creator' => $user->name]))->save();
        if (env('APP_ENV') === 'production') {
            //微信通知法务
            $legal = User::find(getProcessStaffId('legal-affairs', 'invoice'));
            $legalMa = User::find(getProcessStaffId('legal-affairs-manager', 'invoice'));
            InvoiceReceiptNotificationJob::dispatch($legal, '有一笔新的收款待认领')->onQueue('data-clean');
            InvoiceReceiptNotificationJob::dispatch($legalMa, '有一笔新的收款待认领')->onQueue('data-clean');
        }

        return $this->response()->item($invoiceReceipt, new InvoiceReceiptTransformer())->setStatusCode(201);
    }

    public function update(InvoiceReceiptRequest $request, InvoiceReceipt $invoiceReceipt)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('finance')) {
            abort(403, '无操作权限');
        }

        if ($invoiceReceipt->claim_status == 1) {
            abort(403, '已认领，无法修改');
        }

        $invoiceReceipt->update($request->all());

        return $this->response()->noContent()->setStatusCode(200);
    }

    public function confirm(Request $request, InvoiceReceipt $invoiceReceipt)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('legal-affairs|legal-affairs-manager|operation')) {
            abort(403, '无操作权限');
        }
        $contractReceiveDate = ContractReceiveDate::find($request->receive_date_id);
        $contractReceiveDate->update(['receive_status' => 1, 'invoice_receipt_id' => $invoiceReceipt->id]);
        if (env('APP_ENV') === 'production') {
            //通知合同的bd
            $contract = Contract::find($contractReceiveDate->contract_id);
            $bd = User::find($contract->applicant);
            if ($bd->weixin_openid) {
                InvoiceReceiptNotificationJob::dispatch($bd, '合同' . $contract->contract_number . '有一笔收款已认领')->onQueue('data-clean');
            } else {
                //通知法务主管提醒bd关联公众号
                $legalMa = User::find(getProcessStaffId('legal-affairs-manager', 'invoice'));
                InvoiceReceiptNotificationJob::dispatch($legalMa, 'bd' . $bd->name . '的中台账号未关联公众号，请提醒')->onQueue('data-clean');
            }

            //通知运营 运营不在流程线中 暂不做配置
            $operation = User::query()->where('phone', 13661874698)->first();
            InvoiceReceiptNotificationJob::dispatch($operation, '合同' . $contract->contract_number . '有一笔收款已认领')->onQueue('data-clean');
        }

        $invoiceReceipt->claim_status = 1;
        $invoiceReceipt->update();

        return $this->response()->noContent()->setStatusCode(200);
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'invoice_receipt');
    }
}