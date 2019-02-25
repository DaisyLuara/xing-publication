<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Auth;

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
            ->orderByDesc('user_coupon_batches.created_at');
    }

    public function userHonors()
    {
        return $this->belongsToMany(ArMemberHonor::class, 'news_user_honour', 'uid', 'xid');
    }

}
