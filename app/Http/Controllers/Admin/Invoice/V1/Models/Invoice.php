<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;

class Invoice extends Model
{
    protected $fillable=[
        'contract_id',
        'applicant',
        'handler',
        'type',
        'taxpayer_num',
        'phone',
        'address',
        'account_bank',
        'account_number',
        'status',
        'receive_status',
        'kind',
        'total',
        'remark',
        'create_user_id',
    ];

    public function invoiceContent(){
        return $this->hasMany(InvoiceContent::class,'invoice_id','id');
    }

    public function contract(){
        return $this->belongsTo(Contract::class,'contract_id','id');
    }

    public function handlerUser(){
        return $this->belongsTo(User::class,'handler','id');
    }
}
