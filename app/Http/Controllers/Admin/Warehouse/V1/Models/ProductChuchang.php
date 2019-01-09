<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use App\Models\Model;

class ProductChuchang extends Model
{
    protected $table = 'erp_product_chuchangs';
    protected $fillable = [
        'contract_id',
        'product_content',
    ];

    public $timestamps = false;

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
}