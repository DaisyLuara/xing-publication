<?php


namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

//use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use App\Models\Model;

class LocationProduct extends Model
{
    protected $table = 'erp_location_products';

    protected $fillable = [
        'location_id',
        'product_id',
        'stock',
    ];
    public $timestamps = false;

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_sku', 'sku');
    }
}