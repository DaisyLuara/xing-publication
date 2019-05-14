<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 广告投放
 */
class AdLaunch extends Model
{

    protected $connection = 'ar';
    public $table = 'avr_ad_oid';
    protected $primaryKey = 'aoid';
    public $timestamps = false;

    public $fillable = [
        'atiid',
        'marketid',
        'oid',
        'piid',
        'sdate',
        'edate',
        'visiable', //1 运营中 0 下架
        'only',  //1 唯一性 0 非唯一
        'date',
        'clientdate'
    ];

    //广告方案
    public function ad_plan(): BelongsTo
    {
        return $this->belongsTo(AdPlan::class, 'atiid', 'atiid');
    }

    //场地
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    //点位
    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    //节目
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'piid', 'id');
    }

}
