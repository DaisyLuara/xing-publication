<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/6
 * Time: 下午2:25
 */

namespace App\Http\Controllers\Admin\Report\V1\Models;


use App\Models\Model;
use App\Scopes\MCExhibitionPointScope;

/**
 * App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday
 *
 * @property int $id
 * @property int $oid
 * @property string $belong
 * @property int $exposuretimes
 * @property int $looktimes
 * @property int $playtimes7
 * @property int $playtimes15
 * @property int $scantimes
 * @property string $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereExposuretimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereLooktimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday wherePlaytimes15($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday wherePlaytimes7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday whereScantimes($value)
 * @mixin \Eloquent
 */
class XsFaceCountToday extends Model
{
    protected $table = 'xs_face_count_today';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MCExhibitionPointScope());
    }
}