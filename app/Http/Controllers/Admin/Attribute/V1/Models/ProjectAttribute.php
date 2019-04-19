<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Attribute\V1\Models\ProjectAttribute
 *
 * @property int $attribute_id 属性主键
 * @property int $project_id 节目主键
 * @property string $belong 节目别称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\ProjectAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\ProjectAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\ProjectAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\ProjectAttribute whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\ProjectAttribute whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\ProjectAttribute whereProjectId($value)
 * @mixin \Eloquent
 */
class ProjectAttribute extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_project_attributes';
    public $timestamps = false;
    protected $fillable = ['project_id', 'attribute_id', 'belong'];

}
