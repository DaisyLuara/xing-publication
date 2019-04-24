<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute
 *
 * @property int $id ID
 * @property int|null $product_id 产品ID
 * @property int|null $attributes_id 产品属性ID
 * @property string|null $attributes_value 产品属性值
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute|null $attribute
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute whereAttributesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute whereAttributesValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute withoutTrashed()
 * @mixin \Eloquent
 */
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
        return $this->belongsTo(ErpAttribute::class, 'attributes_id', 'id');
    }

}
