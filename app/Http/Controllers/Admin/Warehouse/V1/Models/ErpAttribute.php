<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute
 *
 * @property int $id 属性ID
 * @property string|null $name 产品属性
 * @property string|null $display_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue[] $attributeValues
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute withoutTrashed()
 * @mixin \Eloquent
 */
class ErpAttribute extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'display_name'
    ];

    public function attributeValues()
    {
        return $this->hasMany(ErpAttributeValue::class, 'attribute_id', 'id');
    }
}
