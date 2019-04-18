<?php

namespace App\Http\Controllers\Admin\Device\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Device\V1\Models\FeedBack
 *
 * @property int $id
 * @property string $game_name 游戏名称
 * @property string $device_code 硬件CODE
 * @property string $action 游戏动作
 * @property string $coupon_id
 * @property string $user_nick 用户昵称，混淆的淘宝NIck
 * @property int $draw_result 抽奖结果 ，如果传入，0：表示没中奖，1：表示中奖。该值必须是0或者1，传入其他失败。
 * @property string $outer_biz_id 数据外部编码，保证数据唯一性
 * @property string|null $op_time 操作时间
 * @property string $outer_user 硬件识别的用户标识
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereDeviceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereDrawResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereGameName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereOpTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereOuterBizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereOuterUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Device\V1\Models\FeedBack whereUserNick($value)
 * @mixin \Eloquent
 */
class FeedBack extends Model
{
    protected $connection = 'xs';
    public $table = 'game_feedback';

}
