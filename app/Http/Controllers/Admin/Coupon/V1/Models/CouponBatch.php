<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Http\Controllers\Admin\Activity\V1\Models\Activity;
use App\Http\Controllers\Admin\Activity\V1\Models\ActivityCouponBatch;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\MarketConfig;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Customer;
use App\Models\Model;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch
 *
 * @property int $id
 * @property int $company_id
 * @property int|null $create_user_id
 * @property int $create_customer_id 创建客户id
 * @property int $bd_user_id 关联BD
 * @property string|null $image_url
 * @property string|null $bs_image_url 大屏图片链接
 * @property string|null $name
 * @property string|null $third_code 第三方优惠券特征码
 * @property string|null $description
 * @property int $amount 金额
 * @property int $count 库存总数
 * @property int $stock 剩余库存
 * @property int $people_max_get 每人最大获取数
 * @property int $pmg_status 是否开启每人无限领取,1:开启,0:关闭
 * @property int $day_max_get 每天最大获取数
 * @property int $dmg_status 是否开启每天无限领取,1:开启,0:关闭
 * @property int $is_fixed_date 是否固定日期,1:固定,0:不固定
 * @property int $delay_effective_day 延后生效天数
 * @property int $effective_day 有效天数
 * @property string|null $start_date 开始日期
 * @property string|null $end_date 结束日期
 * @property int $is_active 1 启用,0 停用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $type
 * @property string $redirect_url
 * @property string $title 标题
 * @property int $campaign_id 活动ID
 * @property int|null $credit 兑换积分
 * @property int $sort_order 活动ID
 * @property int $dynamic_stock_status 是否计算 动态库存 0:否 1: 是
 * @property int $write_off_status 是否是系统核销  0:否 1: 是
 * @property int $wechat_coupon_batch_id 微信卡券ID
 * @property string|null $scene_type 场景类型 - 1:商场通用,2:商场自营,3:商户通用,4:商户自营
 * @property int|null $write_off_mid 核销商场
 * @property array|null $write_off_sid 核销商户
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Activity\V1\Models\ActivityCouponBatch[] $activityCouponBatches
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Coupon\V1\Models\Coupon[] $coupon
 * @property-read \App\Http\Controllers\Admin\Coupon\V1\Models\CouponCountLog $couponCountLog
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch[] $marketPointCouponBatches
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy[] $policy
 * @property-read \App\Models\User|null $user
 * @property-read \App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch $wechat
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Customer[] $writeOffCustomers
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market|null $writeOffMarket
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereBdUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereBsImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereCreateCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereDayMaxGet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereDelayEffectiveDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereDmgStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereDynamicStockStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereEffectiveDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereIsFixedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch wherePeopleMaxGet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch wherePmgStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereRedirectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereSceneType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereThirdCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereWechatCouponBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereWriteOffMid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereWriteOffSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponBatch whereWriteOffStatus($value)
 * @mixin \Eloquent
 */
class CouponBatch extends Model
{

    protected $fillable = [
        'company_id',
        'create_user_id',
        'bd_user_id',
        'image_url',
        'bs_image_url',
        'amount',
        'count',
        'stock',
        'people_max_get',
        'pmg_status',
        'day_max_get',
        'dmg_status',
        'is_fixed_date',
        'delay_effective_day',
        'effective_day',
        'start_date',
        'end_date',
        'is_active',
        'name',
        'description',
        'third_code',
        'type',
        'redirect_url',
        'title',
        'campaign_id',
        'marketid',
        'oid',
        'credit',
        'sort_order',
        'dynamic_stock_status',
        'write_off_status',
        'channel',
        'wechat_coupon_batch_id',
        'scene_type',
        'write_off_mid',
        'write_off_sid',
    ];

    protected $casts = [ 'write_off_sid' => 'array' ];

    public function coupon()
    {
        return $this->hasMany(Coupon::class, 'id', 'coupon_batch_id');
    }

    public function policy()
    {
        return $this->belongsToMany(Policy::class)->withPivot(['rate', 'min_age', 'max_age', 'gender', 'type']);
    }

    public function couponCountLog()
    {
        return $this->hasOne(CouponCountLog::class, 'id', 'coupon_batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'create_customer_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function wechat()
    {
        return $this->hasOne(WechatCouponBatch::class, 'id', 'wechat_coupon_batch_id')->withDefault();
    }

    public function activityCouponBatches()
    {
        return $this->hasMany(ActivityCouponBatch::class, 'coupon_batch_id', 'id');
    }

    public function marketPointCouponBatches()
    {
        return $this->hasMany(MarketPointCouponBatch::class, 'coupon_batch_id', 'id');
    }

    public function writeOffMarket()
    {
        return $this->belongsTo(Market::class, 'write_off_mid', 'marketid');
    }

    public function writeOffCustomers()
    {
        return $this->belongsToMany(Customer::class, 'coupon_batch_write_off_customers')->withTimestamps();
    }

}