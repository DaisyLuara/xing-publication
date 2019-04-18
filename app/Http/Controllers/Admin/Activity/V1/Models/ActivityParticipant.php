<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo;
use App\Models\Model;

/**
 * Class ActivityParticipant
 * @package App\Http\Controllers\Admin\Activity\V1\Models
 * @property int uid
 * @property string username
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
