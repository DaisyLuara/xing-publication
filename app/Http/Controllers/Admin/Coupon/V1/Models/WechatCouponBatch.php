<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Models\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class WechatCouponBatch extends Model
{
    use LogsActivity;

    protected $fillable = [
        'wechat_authorizer_id',
        'expire_at',
        'card_id',
    ];
}