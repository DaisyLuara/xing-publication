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

/**
 * App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch
 *
 * @property int $id
 * @property int $wechat_authorizer_id
 * @property int $expire_at
 * @property string $card_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch whereWechatAuthorizerId($value)
 * @mixin \Eloquent
 */
class WechatCouponBatch extends Model
{
    use LogsActivity;

    protected $fillable = [
        'wechat_authorizer_id',
        'expire_at',
        'card_id',
    ];
}