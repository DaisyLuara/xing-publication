<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\PointShare
 *
 * @property int $oid 点位ID
 * @property int $site 场地主 0:关闭 1：开启
 * @property int $vipad VIP广告主 0:关闭 1：开启
 * @property int $ad 广告主 0:关闭 1：开启
 * @property int $agent 代理0：关闭 1：开启
 * @property int $offer 报刊价
 * @property float $offer_off 报刊价系数%
 * @property int $mad 曝光价
 * @property float $mad_off 曝光价系数%
 * @property int $play 冠名价
 * @property float $play_off 冠名价系数%
 * @property int $qrcode 链接跳转
 * @property float $qrcode_off 链接跳转系数%
 * @property int $wx_pa 订阅/公众号
 * @property float $wx_pa_off 订阅/公众号系数%
 * @property int $wx_mp 小程序
 * @property float $wx_mp_off 小程序系数%
 * @property int $app APP
 * @property float $app_off APP系数%
 * @property int $phone 手机号
 * @property float $phone_off 手机号系数%
 * @property int $coupon 卷
 * @property float $coupon_off 卷系数%
 * @property string $date
 * @property int $clientdate
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereAd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereAppOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereCouponOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereMad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereMadOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereOfferOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare wherePhoneOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare wherePlay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare wherePlayOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereQrcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereQrcodeOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereVipad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereWxMp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereWxMpOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereWxPa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointShare whereWxPaOff($value)
 * @mixin \Eloquent
 */
class PointShare extends ArModel
{
    public $table = 'avr_official_share';
    protected $primaryKey = 'oid';

    protected $fillable = [
        'site', 'vipad', 'ad', 'agent', 'offer', 'offer_off',
        'mad', 'mad_off', 'play', 'play_off', 'qrcode', 'qrcode_off', 'wx_pa',
        'wx_pa_off', 'wx_mp', 'wx_mp_off', 'app', 'app_off', 'phone', 'phone_off',
        'coupon', 'coupon_off', 'date', 'clientdate'
    ];

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }
}
