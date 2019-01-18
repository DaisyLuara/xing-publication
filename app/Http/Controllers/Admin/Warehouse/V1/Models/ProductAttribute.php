<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    protected $table = 'erp_product_attributes';
    use SoftDeletes;

    public $fillable = [
        'product_id',
        'attributes_id',
        'attributes_value'
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attributes_id', 'id');
    }

}