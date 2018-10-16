<?php

namespace App\Http\Controllers\Admin\Contract\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Models\Model;
use App\Models\User;

class Contract extends Model
{
    public $fillable=[
        'contract_number',
        'name',
        'company_id',
        'applicant',
        'processing_person',
        'status',
        'processing_person',
        'type',
        'receive_date',
        'content',
        'remark'
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
}
