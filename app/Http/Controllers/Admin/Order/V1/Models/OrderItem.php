<?php

namespace App\Http\Controllers\Admin\Order\V1\Models;

use App\Models\Model;
use App\Http\Controllers\Admin\Product\V1\Models\Product;
use App\Http\Controllers\Admin\Product\V1\Models\ProductSku;

class OrderItem extends Model
{
    protected $table = 'shop_order_items';
    protected $fillable = ['amount', 'price', 'rating', 'review', 'reviewed_at'];
    protected $dates = ['reviewed_at'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
