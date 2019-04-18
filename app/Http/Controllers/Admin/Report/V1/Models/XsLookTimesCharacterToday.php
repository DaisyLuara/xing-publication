<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/7
 * Time: 上午10:59
 */

namespace App\Http\Controllers\Admin\Report\V1\Models;


use App\Models\Model;
use App\Scopes\MCExhibitionPointScope;

/**
 * App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday
 *
 * @property int $id
 * @property int $oid
 * @property string $belong
 * @property string $time
 * @property int $century10_bnum
 * @property int $century10_gnum
 * @property int $century00_bnum
 * @property int $century00_gnum
 * @property int $century90_bnum
 * @property int $century90_gnum
 * @property int $century80_bnum
 * @property int $century80_gnum
 * @property int $century70_bnum
 * @property int $century70_gnum
 * @property string $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury00Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury00Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury10Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury10Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury70Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury70Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury80Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury80Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury90Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereCentury90Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday whereTime($value)
 * @mixin \Eloquent
 */
class XsLookTimesCharacterToday extends Model
{
    protected $table = "xs_looktimes_character_today";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MCExhibitionPointScope());
    }
}