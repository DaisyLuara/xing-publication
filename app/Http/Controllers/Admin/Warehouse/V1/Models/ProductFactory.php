<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;

class ProductFactory extends Model
{
    protected $table = 'erp_product_chuchangs';
    protected $fillable = [
        'contract_id',
        'product_content',
    ];

    public $timestamps = false;
}