<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/31
 * Time: 17:44
 */

namespace App\Http\Controllers\Admin\WeChat\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking
 *
 * @property int $id
 * @property int $ar_user_id
 * @property string $ar_user_name
 * @property int $point_id
 * @property string $point_name
 * @property int $scene_id
 * @property string $scene_name
 * @property int $looknum_average
 * @property int $ranking
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereArUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereArUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereLooknumAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking wherePointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking wherePointName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereRanking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereSceneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereSceneName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking whereStartDate($value)
 * @mixin \Eloquent
 */
class WeekRanking extends Model
{
    protected $fillable = [
        'ar_user_id',
        'ar_user_name',
        'point_id',
        'point_name',
        'scene_id',
        'scene_name',
        'looknum_average',
        'ranking',
        'start_date',
        'end_date',
        'date'
    ];
}