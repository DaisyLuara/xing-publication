<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Point\V1\Models\Area;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch
 *
 * @property int $aoid
 * @property int $atid 行业ID
 * @property int $atiid 广告主ID
 * @property int $aid 广告ID
 * @property int $areaid 区域ID,0为通用
 * @property int $marketid 商场ID,0为通用
 * @property int $oid 门店ID,0为通用
 * @property int $ktime 秒为单位
 * @property int $sdate 开始日期
 * @property int $edate 结束日期
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdTrade $adTrade
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\Advertisement $advertisement
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\Advertiser $advertiser
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Area $area
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereAid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereAoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereAtid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereAtiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereKtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereSdate($value)
 * @mixin \Eloquent
 */
class AdLaunch extends Model
{

    protected $connection = 'ar';
    public $table = 'avr_ad_oid';
    protected $primaryKey = 'aoid';
    public $timestamps = false;

    public $fillable = [
        'atid',
        'atiid',
        'aid',
        'areaid',
        'marketid',
        'oid',
        'ktime',
        'sdate',
        'edate',
        'date',
        'clientdate'
    ];

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class, 'atiid', 'atiid');
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'aid', 'aid');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }
}
