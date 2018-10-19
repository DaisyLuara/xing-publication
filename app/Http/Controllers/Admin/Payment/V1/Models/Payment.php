<?php

namespace App\Http\Controllers\Admin\Payment\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;

class Payment extends Model
{
    protected $fillable=[
        'contract_id',
        'applicant',
        'handler',
        'amount',
        'type',
        'reason',
        'payee',
        'account_bank',
        'account_number',
        'remark',
        'status',
        'receive_status',
    ];

    public function contract(){
        return $this->belongsTo(Contract::class,'contract_id','id');
    }

    public function applicantUser(){
        return $this->belongsTo(User::class,'applicant','id');
    }

    public function handlerUser(){
        return $this->belongsTo(User::class,'handler','id');
    }
}
