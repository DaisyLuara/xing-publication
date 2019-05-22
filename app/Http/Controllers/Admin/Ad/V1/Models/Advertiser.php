<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Ad\V1\Models\Advertiser
 *
 * @property int $atiid
 * @property int $atid 广告行业
 * @property string $name 名称
 * @property string $icon 图标
 * @property string $info 详情
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdTrade $adTrade
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser whereAtid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser whereAtiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertiser whereName($value)
 * @mixin \Eloquent
 */
class Advertiser extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_trade_info';
    protected $primaryKey = 'atiid';
    public $timestamps = false;

    public $fillable = [
        'atid',
        'name',
        'icon',
        'info',
        'date',
        'clientdate'
    ];

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }
}
