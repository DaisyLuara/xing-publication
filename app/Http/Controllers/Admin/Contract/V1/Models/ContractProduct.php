<?php

namespace App\Http\Controllers\Admin\Contract\V1\Models;

use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use App\Models\Model;

class ContractProduct extends Model
{
    protected $fillable = [
        'contract_id',
        'product_name',
        'product_color',
        'product_stock'
    ];
    public $timestamps = false;

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function invoiceReceipt()
    {
        return $this->belongsTo(InvoiceReceipt::class, 'invoice_receipt_id', 'id');
    }
}