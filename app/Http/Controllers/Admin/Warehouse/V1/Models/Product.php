<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'erp_products';

    public $fillable = [
        'sku',
        'supplier'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'supplier', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }

}
