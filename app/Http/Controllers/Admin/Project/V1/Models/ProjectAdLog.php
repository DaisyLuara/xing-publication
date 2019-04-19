<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog
 *
 * @property int $id
 * @property int $oid 点位ID
 * @property int $piid 节目id
 * @property int $wiid 授权广告ID
 * @property string $unionid
 * @property int $offline 0: 不是 1：是
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $project
 * @property-read \App\Http\Controllers\Admin\WeChat\V1\Models\WxThird $wxThird
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog whereOffline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog wherePiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog whereUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLog whereWiid($value)
 * @mixin \Eloquent
 */
class ProjectAdLog extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_ad_log';

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function wxThird()
    {
        return $this->belongsTo(WxThird::class, 'wiid', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'piid', 'id');
    }
}