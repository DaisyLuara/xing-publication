<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Models\Model;

class MallcooScoreHistory extends Model
{
    protected $table = 'mallcoo_score_histories';

    protected $fillable = [
        'score',
        'description',
    ];

    public function thirdPartyUser()
    {
        return $this->belongsTo(ThirdPartyUser::class, 'mallcoo_open_user_id', 'mallcoo_open_user_id');
    }

}
