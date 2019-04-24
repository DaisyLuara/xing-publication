<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/22
 * Time: 下午2:59
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character
 *
 * @property int $id
 * @property int $oid
 * @property string $belong
 * @property string $time
 * @property int $century00_bnum
 * @property int $century00_gnum
 * @property int $century90_bnum
 * @property int $century90_gnum
 * @property int $century80_bnum
 * @property int $century80_gnum
 * @property int $century70_bnum
 * @property int $century70_gnum
 * @property int $century10_bnum
 * @property int $century10_gnum
 * @property string|null $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury00Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury00Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury10Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury10Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury70Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury70Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury80Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury80Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury90Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereCentury90Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Character whereTime($value)
 * @mixin \Eloquent
 */
class XsFacePlayTimes15Character extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_face_playtimes15_character_count';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}