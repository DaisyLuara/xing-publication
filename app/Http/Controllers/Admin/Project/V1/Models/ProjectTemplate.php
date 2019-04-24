<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate
 *
 * @property int $tid
 * @property string $name 名称
 * @property string $type 类型
 * @property string $icon 图标
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate whereTid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate whereType($value)
 * @mixin \Eloquent
 */
class ProjectTemplate extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_template';
    protected $primaryKey = 'tid';
    public $timestamps = false;
}
