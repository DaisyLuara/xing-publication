<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\Model;

/**
 * Class ArWxUser
 *
 */
class ArWxUser extends Model
{
    protected $connection = 'ar';
    protected $table = 'news_user_open';
    public $timestamps = false;

}
