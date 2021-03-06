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
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class InvoiceReceiptController extends Controller
{
    public function show(InvoiceReceipt $invoiceReceipt): Response
    {
        return $this->response()->item($invoiceReceipt, new InvoiceReceiptTransformer());
    }

    public function index(Request $request, InvoiceReceipt $invoiceReceipt): Response
    {
        $query = $invoiceReceipt->query();
        if ($request->get('start_date') && $request->get('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }

        if ($request->filled('name')) {
            $query->where('receipt_company', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->filled('claim_status')) {
            $query->where('claim_status', $request->get('claim_status'));
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user')) {
            $query->whereHas('receiveDate', static function ($q) use ($user) {
                $q->whereHas('contract', static function ($q) use ($user) {
                    $q->where('owner', $user->id);
                });
            });
        }

        if ($user->hasRole('bd-manager')) {
            $query->whereHas('receiveDate', static function ($q) use ($user) {
                $q->whereHas('contract', static function ($q) use ($user) {
                    $q->whereHas('ownerUser', static function ($q) use ($user) {
                        $q->where('parent_id', $user->id);
                    });
                });
            });
        }

        $invoiceReceipts = $query->orderByDesc('id')->paginate(10);

        return $this->response()->paginator($invoiceReceipts, new InvoiceReceiptTransformer());
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
            $legal = User::query()->find(getProcessStaffId('legal-affairs', 'invoice'));
            $legalMa = User::query()->find(getProcessStaffId('legal-affairs-manager', 'invoice'));
            InvoiceReceiptNotificationJob::dispatch($legal, '有一笔新的收款待认领')->onQueue('data-clean');
            InvoiceReceiptNotificationJob::dispatch($legalMa, '有一笔新的收款待认领')->onQueue('data-clean');
        }

        activity('create_invoice_receipt')
            ->causedBy($user)
            ->performedOn($invoiceReceipt)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增开票收款');

        return $this->response()->item($invoiceReceipt, new InvoiceReceiptTransformer())->setStatusCode(201);
    }

    public function update(InvoiceReceiptRequest $request, InvoiceReceipt $invoiceReceipt)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('finance')) {
            abort(403, '无操作权限');
        }

        if ($invoiceReceipt->claim_status === 1) {
            abort(403, '已认领，无法修改');
        }

        $invoiceReceipt->update($request->all());

        activity('update_invoice_receipt')
            ->causedBy($user)
            ->performedOn($invoiceReceipt)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑开票收款');

        return $this->response()->noContent()->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param InvoiceReceipt $invoiceReceipt
     * @return \Dingo\Api\Http\Response
     */
    public function confirm(Request $request, InvoiceReceipt $invoiceReceipt): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('legal-affairs|legal-affairs-manager|operation')) {
            abort(403, '无操作权限');
        }
        /** @var  ContractReceiveDate $contractReceiveDate */
        $contractReceiveDate = ContractReceiveDate::query()->find($request->get('receive_date_id'));
        $contractReceiveDate->update(['receive_status' => 1, 'invoice_receipt_id' => $invoiceReceipt->id]);

        $invoiceReceipt->claim_status = 1;
        $invoiceReceipt->update();

        if (env('APP_ENV') === 'production') {
            //通知合同的bd
            $contract = Contract::query()->find($contractReceiveDate->contract_id);
            $bd = User::query()->find($contract->applicant);

            $content = '合同有一笔收款已认领' . "\r\n" . '合同编号：' . $contract->contract_number . "\r\n" . '合同公司：' . $contract->company->name . "\r\n" . '收款金额：' . $invoiceReceipt->receipt_money;
            if ($bd->weixin_openid) {
                InvoiceReceiptNotificationJob::dispatch($bd, $content)->onQueue('data-clean');
            } else {
                //通知法务主管提醒bd关联公众号
                $legalMa = User::query()->find(getProcessStaffId('legal-affairs-manager', 'invoice'));
                InvoiceReceiptNotificationJob::dispatch($legalMa, $bd->name . '的中台账号未关联公众号，请提醒')->onQueue('data-clean');
            }

            //通知运营 运营不在流程线中 暂不做配置
            $operation = User::query()->where('phone', 13661874698)->first();
            InvoiceReceiptNotificationJob::dispatch($operation, $content)->onQueue('data-clean');
        }

        activity('confirm_invoice_receipt')
            ->causedBy($user)
            ->performedOn($invoiceReceipt)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('确认开票收款');

        return $this->response()->noContent()->setStatusCode(200);
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'invoice_receipt');
    }
}