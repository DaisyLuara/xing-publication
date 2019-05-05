<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Auth;

/**
 * App\Http\Controllers\Admin\User\V1\Models\ArMemberSession
 *
 * @property int $uid
 * @property int $areaid 区域ID
 * @property int $marketid 场地ID
 * @property int $oid 点位ID
 * @property string $res 来源：h5,publick,subk,apps,app
 * @property string $z
 * @property string $username
 * @property string $lastip
 * @property string $lastactivity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor[] $userHonors
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereLastactivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereLastip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereRes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberSession whereZ($value)
 * @mixin \Eloquent
 */
class ArMemberSession extends Authenticatable implements JWTSubject
{
    protected $connection = 'ar';
    public $table = 'news_sessions';
    protected $primaryKey = 'uid';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'lastip', 'lastactivity',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userCouponBatches()
    {
        return $this->setConnection('mysql')->belongsToMany(CouponBatch::class, 'user_coupon_batches', 'member_uid', 'coupon_batch_id')
            ->withTimestamps()
            ->withPivot('belong')
            ->orderByDesc('user_coupon_batches.created_at');
    }

    public function userHonors()
    {
        return $this->belongsToMany(ArMemberHonor::class, 'news_user_honour', 'uid', 'xid');
    }

    public function arMember()
    {
        return $this->belongsTo(ArMember::class, 'uid', 'uid');
    }

}
