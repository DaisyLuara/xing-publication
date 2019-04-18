<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange
 *
 * @property int $id
 * @property string|null $sku 产品SKU
 * @property int|null $out_location 调出库位
 * @property int|null $in_location 调入库位
 * @property int|null $num 调拨数量
 * @property string|null $remark 调拨记录备注
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Http\Controllers\Admin\Warehouse\V1\Models\Location|null $inLocation
 * @property-read \App\Http\Controllers\Admin\Warehouse\V1\Models\Location|null $outLocation
 * @property-read \App\Http\Controllers\Admin\Warehouse\V1\Models\Product $product
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereInLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereOutLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange withoutTrashed()
 * @mixin \Eloquent
 */
class WarehouseChange extends Model
{
    use SoftDeletes;

    protected $table = 'erp_warehouse_changes';

    public $fillable = [
        'product_id',
        'out_location',
        'in_location',
        'num',
        'remark'
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function outLocation(){
        return $this->belongsTo(Location::class,'out_location','id');
    }

    public function inLocation(){
        return $this->belongsTo(Location::class, 'in_location', 'id');
    }

}
