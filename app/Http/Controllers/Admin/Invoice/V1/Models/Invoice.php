<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Admin\Media\V1\Models\Media;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'applicant',
        'handler',
        'type',
        'invoice_company_id',
        'status',
        'kind',
        'total',
        'total_text',
        'remark',
        'bd_ma_message',
        'legal_ma_message',
        'drawer'
    ];

    public function invoiceContent()
    {
        return $this->hasMany(InvoiceContent::class, 'invoice_id', 'id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function handlerUser()
    {
        return $this->belongsTo(User::class, 'handler', 'id');
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function invoiceCompany()
    {
        return $this->belongsTo(InvoiceCompany::class, 'invoice_company_id', 'id');
    }

    public function invoiceHistory()
    {
        return $this->hasMany(InvoiceHistory::class, 'invoice_id', 'id');
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'invoice_media', 'invoice_id', 'media_id');
    }
}
