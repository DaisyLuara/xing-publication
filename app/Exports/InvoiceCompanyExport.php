<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InvoiceCompanyExport extends BaseExport
{

    private $name;//公司名称

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->fileName = '票据-开票公司列表';
    }

    public function collection()
    {

        $query = InvoiceCompany::query();

        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('user_id', $user->id);
        }

        $invoiceCompanies = $query->orderByDesc('created_at')->get()
            ->map(function ($invoiceCompany) {
                return [
                    'id' => $invoiceCompany->id,
                    'name' => $invoiceCompany->name,
                    'address' => $invoiceCompany->address,
                    'taxpayer_num' => "\t" . $invoiceCompany->taxpayer_num . "\t",
                    'phone' => "\t" . $invoiceCompany->phone . "\t",
                    'telephone' => "\t" . $invoiceCompany->telephone . "\t",
                    'account_bank' => $invoiceCompany->account_bank,
                    'account_number' => "\t" . $invoiceCompany->account_number . "\t",
                    'created_at' => $invoiceCompany->created_at->toDateString(),
                    'updated_at' => $invoiceCompany->updated_at->toDateString()
                ];
            })->toArray();

        $header = ['开票公司ID', '开票公司', '地址', '纳税人识别号', '手机号', '座机电话', '开户银行', '开户行账号', '创建时间', '最后操作时间'];

        $this->header_num = count($header);
        array_unshift($invoiceCompanies, $header, $header);
        $this->data = $data = collect($invoiceCompanies);

        return $data;
    }


}