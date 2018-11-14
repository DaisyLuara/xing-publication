<?php

namespace App\Http\Controllers\Admin\Contract\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    public $fillable = [
        'contract_number',
        'name',
        'company_id',
        'applicant',
        'handler',
        'status',
        'processing_person',
        'type',
        'amount',
        'remark',
        'legal_message',
        'legal_ma_message',
        'bd_ma_message'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function handlerUser()
    {
        return $this->belongsTo(User::class, 'handler', 'id');
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id');
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'contract_id', 'id');
    }

    public function receiveDate()
    {
        return $this->hasMany(ContractReceiveDate::class, 'contract_id', 'id');
    }

    public function contractHistory()
    {
        return $this->hasMany(ContractHistory::class, 'contract_id', 'id');
    }
}
