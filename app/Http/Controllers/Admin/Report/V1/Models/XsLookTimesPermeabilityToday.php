<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/6
 * Time: 下午5:01
 */

namespace App\Http\Controllers\Admin\Report\V1\Models;


use App\Models\Model;
use App\Scopes\MCExhibitionPointScope;

/**
 * App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday
 *
 * @property int $id
 * @property int $oid
 * @property string $belong
 * @property int $bnum
 * @property int $gnum
 * @property int $age10b
 * @property int $age10g
 * @property int $age18b
 * @property int $age18g
 * @property int $age30b
 * @property int $age30g
 * @property int $age40b
 * @property int $age40g
 * @property int $age50b
 * @property int $age50g
 * @property int $age60b
 * @property int $age60g
 * @property int $age61b
 * @property int $age61g
 * @property string $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge10b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge10g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge18b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge18g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge30b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge30g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge40b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge40g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge50b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge50g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge60b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge60g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge61b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereAge61g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereBnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereGnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday whereOid($value)
 * @mixin \Eloquent
 */
class XsLookTimesPermeabilityToday extends Model
{
    protected $table = 'xs_looktimes_permeability_today';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MCExhibitionPointScope());
    }
}