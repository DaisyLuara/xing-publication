<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue
 *
 * @property int $id
 * @property int $attribute_id 属性ID
 * @property string|null $value 属性名称
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute $attribute
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue withoutTrashed()
 * @mixin \Eloquent
 */
class ErpAttributeValue extends Model
{
    use SoftDeletes;

    public $fillable = [
        'attribute_id',
        'value'
    ];

    public function attribute()
    {
        return $this->belongsTo(ErpAttribute::class, 'attribute_id', 'id');
    }
}
