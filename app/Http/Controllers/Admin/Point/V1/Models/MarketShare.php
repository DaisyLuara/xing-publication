<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\MarketShare
 *
 * @property int $marketid 场地ID
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
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereAd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereAppOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereCouponOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereMad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereMadOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereOfferOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare wherePhoneOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare wherePlay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare wherePlayOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereQrcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereQrcodeOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereVipad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereWxMp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereWxMpOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereWxPa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketShare whereWxPaOff($value)
 * @mixin \Eloquent
 */
class MarketShare extends ArModel
{
    public $table = 'avr_official_market_share';
    protected $primaryKey = 'marketid';

    protected $fillable = [
        'marketid', 'site', 'vipad', 'ad', 'agent', 'offer', 'offer_off',
        'mad', 'mad_off', 'play', 'play_off', 'qrcode', 'qrcode_off', 'wx_pa',
        'wx_pa_off', 'wx_mp', 'wx_mp_off', 'app', 'app_off', 'phone', 'phone_off',
        'coupon', 'coupon_off', 'date', 'clientdate'
    ];

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }
}
