<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'receive_status',
        'kind',
        'total',
        'total_text',
        'remark',
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
}
