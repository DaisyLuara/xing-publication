<?php

namespace App\Http\Controllers\Admin\Payment\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Admin\Media\V1\Models\Media;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'applicant',
        'handler',
        'amount',
        'type',
        'reason',
        'payment_payee_id',
        'remark',
        'bd_ma_message',
        'legal_message',
        'legal_ma_message',
        'auditor_message',
        'payer',
        'status',
        'receive_status',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function handlerUser()
    {
        return $this->belongsTo(User::class, 'handler', 'id');
    }

    public function paymentPayee()
    {
        return $this->belongsTo(PaymentPayee::class, 'payment_payee_id', 'id');
    }

    public function paymentHistory()
    {
        return $this->hasMany(PaymentHistory::class, 'payment_id', 'id');
    }

    public function media(){
        return $this->belongsToMany(Media::class, 'payment_media', 'payment_id', 'media_id');
    }
}
