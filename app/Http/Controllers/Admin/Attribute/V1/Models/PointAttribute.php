<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute
 *
 * @property int $attribute_id 属性主键
 * @property int $point_id 点位主键
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute wherePointId($value)
 * @mixin \Eloquent
 */
class PointAttribute extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_point_attributes';
    public $timestamps = false;
    protected $fillable = ['point_id', 'attribute_id'];
}
