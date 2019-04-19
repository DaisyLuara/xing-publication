<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/19
 * Time: 下午1:49
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury00Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury00Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury10Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury10Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury70Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury70Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury80Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury80Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury90Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereCentury90Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes whereTime($value)
 * @mixin \Eloquent
 */
class XsFaceCharacterCountTimes extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_character_count_times';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}