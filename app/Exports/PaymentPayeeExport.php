<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentPayeeExport extends BaseExport
{

    private $name;

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->fileName = '票据-收款人管理列表';
    }

    public function collection()
    {
        $query = PaymentPayee::query();
        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('owner', $user->id);
        }

        $paymentPayees = $query->orderByDesc('created_at')->get()
            ->map(function ($paymentPayee) {
                return [
                    'id' => $paymentPayee->id,
                    'name' => $paymentPayee->name,
                    'account_bank' => $paymentPayee->account_bank,
                    'account_number' => "\t" . $paymentPayee->account_number . "\t",
                    'created_at' => $paymentPayee->created_at->toDateString(),
                    'updated_at' => $paymentPayee->updated_at->toDateString()
                ];
            })->toArray();

        $header = ['ID', '收款人姓名', '收款人开户行', '收款人开户账号', '创建时间', '最后操作时间'];

        $this->header_num = count($header);
        array_unshift($paymentPayees, $header, $header);
        $this->data = $data = collect($paymentPayees);

        return $data;
    }


}