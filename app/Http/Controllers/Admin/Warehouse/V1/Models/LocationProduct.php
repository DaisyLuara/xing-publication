<?php


namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct
 *
 * @property int $id
 * @property int|null $location_id 库位ID
 * @property int|null $product_id 产品ID
 * @property int $stock 库存数量
 * @property-read \App\Http\Controllers\Admin\Warehouse\V1\Models\Location|null $location
 * @property-read \App\Http\Controllers\Admin\Warehouse\V1\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct whereStock($value)
 * @mixin \Eloquent
 */
class LocationProduct extends Model
{
    protected $table = 'erp_location_products';

    public $fillable = [
        'location_id',
        'product_id',
        'stock'
    ];
    public $timestamps = false;

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}