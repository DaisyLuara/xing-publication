<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\User\V1\Models\ArMember;
use App\Models\Model;
use App\Models\Customer;

/**
 * App\Http\Controllers\Admin\Coupon\V1\Models\Coupon
 *
 * @property int $id
 * @property int $coupon_batch_id
 * @property string $mobile
 * @property string $code
 * @property string $picm_id 第三方 投放ID
 * @property string $trace_id 第三方 追踪ID
 * @property int $status 0 未领取, 1 已使用, 2 停用, 3 未使用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $wx_user_id
 * @property string $taobao_user_id
 * @property string $order_no 订单编号
 * @property string $order_total 订单编号
 * @property int $media_id 附件
 * @property int $shop_customer_id 核销人员ID
 * @property int $member_uid c端用户UID
 * @property int $qiniu_id 七牛ID
 * @property string $channel 渠道
 * @property int $oid 点位ID
 * @property string $belong 游戏名称
 * @property int $utm_source 来源-1:h5,2:小程序,3:App
 * @property int $merchant_id 商户ID
 * @property string $out_uid 外部用户标识
 * @property int $is_fixed_date 是否固定日期,1:固定,0:不固定
 * @property int $delay_effective_day 延后生效天数
 * @property int $effective_day 有效天数
 * @property \Illuminate\Support\Carbon|null $start_date 开始日期
 * @property \Illuminate\Support\Carbon|null $end_date 结束日期
 * @property \Illuminate\Support\Carbon|null $use_date 使用日期
 * @property-read \App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch $couponBatch
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media $media
 * @property-read \App\Http\Controllers\Admin\User\V1\Models\ArMember $member
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereCouponBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereDelayEffectiveDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereEffectiveDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereIsFixedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereMemberUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereOrderTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereOutUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon wherePicmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereQiniuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereShopCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereTaobaoUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereTraceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereUseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereUtmSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon whereWxUserId($value)
 * @mixin \Eloquent
 */
class Coupon extends Model
{

    protected $dates = ['start_date', 'end_date', 'use_date'];

    protected $fillable = [
        'mobile',
        'coupon_batch_id',
        'code',
        'picm_id',
        'trace_id',
        'status',
        'wx_user_id',
        'taobao_user_id',
        'member_uid',
        'qiniu_id',
        'channel',
        'oid',
        'belong',
        'utm_source',
        'start_date',
        'end_date',
    ];

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class, 'coupon_batch_id', 'id');
    }

    public function media()
    {
        return $this->hasOne(Media::class);
    }

    public function member()
    {
        return $this->belongsTo(ArMember::class, 'member_uid', 'uid');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id', 'id');
    }
}
