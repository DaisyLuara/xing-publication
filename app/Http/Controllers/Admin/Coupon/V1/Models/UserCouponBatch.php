<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Models\Model;
use App\Models\User;

class UserCouponBatch extends Model
{
    protected $fillable = [
        'wx_user_id',
        'tao_user_id',
        'coupon_batch_id',
    ];

}