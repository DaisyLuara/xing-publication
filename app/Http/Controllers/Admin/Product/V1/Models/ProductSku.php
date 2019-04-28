<?php

namespace App\Http\Controllers\Admin\Product\V1\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $table = 'shop_product_skus';
    protected $fillable = ['title', 'description', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
