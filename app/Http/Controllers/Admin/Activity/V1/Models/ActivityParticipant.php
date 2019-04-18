<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo;
use App\Models\Model;

/**
 * Class ActivityParticipant
 *
 * @package App\Http\Controllers\Admin\Activity\V1\Models
 * @property int uid
 * @property string username
 * @property int $auid
 * @property int $uid 用户uid
 * @property int $aid 奖品ID
 * @property int $aiid 奖品详情id
 * @property string $username 用户昵称
 * @property int $avrid 嗨元素ID
 * @property int $avridtmp 嗨元素ID副本
 * @property float $value 数量
 * @property float $valuetmp 临时副本
 * @property string|null $kid 标识
 * @property string|null $kidtmp 标识副本
 * @property string|null $link 链接
 * @property string|null $linktmp 链接副本
 * @property int $views 点击数
 * @property int $day_num 当天次数
 * @property int $user_num 用户总次数
 * @property int $pass 0：默认 1：生效 2：失效
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo $arUserInfo
 * @property-read \App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser $arWxUser
 * @property-read \App\Http\Controllers\Admin\Activity\V1\Models\PlayingType $playingType
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereAid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereAiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereAuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereAvrid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereAvridtmp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereDayNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereKid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereKidtmp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereLinktmp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant wherePass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereUserNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereValuetmp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant whereViews($value)
 * @mixin \Eloquent
 */
class ActivityParticipant extends Model
{
    protected $connection = 'ar';
    protected $table = 'ar_award_list';
    protected $primaryKey = 'auid';
    public $timestamps = false;

    public function playingType()
    {
        return $this->belongsTo(PlayingType::class, 'aid', 'aid');
    }

    public function arUserInfo()
    {
        return $this->belongsTo(ArMemberInfo::class, 'uid', 'uid');
    }

    public function arWxUser()
    {
        return $this->belongsTo(ArWxUser::class, 'uid', 'uid');
    }

}
