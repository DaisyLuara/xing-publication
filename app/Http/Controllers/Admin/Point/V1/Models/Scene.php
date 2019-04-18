<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\Scene
 *
 * @property int $sid
 * @property string $name 名称
 * @property string $req 要求
 * @property int $checknum 考核人数
 * @property string $ps 备注
 * @property string $icon 图标
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Point\V1\Models\Point[] $points
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene whereChecknum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene wherePs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene whereReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Scene whereSid($value)
 * @mixin \Eloquent
 */
class Scene extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_scene';
    protected $primaryKey = 'sid';

    public function points()
    {
        return $this->hasMany(Point::class, 'sid', 'sid');
    }
}
