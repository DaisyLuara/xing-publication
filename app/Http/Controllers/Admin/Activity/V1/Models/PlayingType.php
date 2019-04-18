<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Activity\V1\Models\PlayingType
 *
 * @property int $aid
 * @property int $cid 公司ID
 * @property int $pid 产品ID
 * @property string $image 图片
 * @property string $awardkey 关键key
 * @property string $name 名称
 * @property int $wiid 授权链接
 * @property string|null $link 授权链接地址
 * @property int $marketid 场地ID
 * @property int $oid 点位ID
 * @property string $passed nums：人数清算 hours：小时清算 day：当天清算 week：当周清算 month：当月清算 year：当年清算  forever：永久有效
 * @property int $pass_num 清算时间，最低1小时
 * @property string $type 类型 game：排行榜 alltop：人气票选 honour：成就
 * @property int $r_num 6纯数字 6000字母+数字
 * @property string $idmode normal：正常 proxy：代理
 * @property int $odds 几率
 * @property float $all_value 总数
 * @property float $mob_value 剩余数
 * @property float $max_value 单人单次最大数
 * @property int $max_user 最大人数
 * @property int $day_num 每日最大次数
 * @property int $user_num 用户最大次数
 * @property string $mode 模式 fixed：固定 random：随机 div：自定义
 * @property string $user_mode 人数模式 fixed：固定 random：随机 div：自定义
 * @property string $value_mode only：唯一 min:最小值 max:最大值 add:累积 replace:替换 reduce:减小 most:重复
 * @property int $user_per 用户参与确认 0: 不是 1：是
 * @property int $views 数量
 * @property int $openid 关联奖品ID
 * @property int $xid 奖励成就ID
 * @property int $sdate 开始时间
 * @property int $edate 截止时间
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereAid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereAllValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereAwardkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereDayNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereIdmode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereMaxUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereMaxValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereMobValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereOdds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType wherePassNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType wherePassed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereRNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereUserMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereUserNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereUserPer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereValueMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereWiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\PlayingType whereXid($value)
 * @mixin \Eloquent
 */
class PlayingType extends Model
{
    protected $connection = 'ar';
    protected $table = 'ar_award_type';
    protected $primaryKey = 'aid';
    public $timestamps = false;

}
