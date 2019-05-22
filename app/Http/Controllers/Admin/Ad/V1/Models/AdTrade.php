<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

/**
 * 广告行业
 * App\Http\Controllers\Admin\Ad\V1\Models\AdTrade
 *
 * @property int $atid
 * @property string $name 名称
 * @property string $icon 图标
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade whereAtid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdTrade whereName($value)
 * @mixin \Eloquent
 */
class AdTrade extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_trade';
    protected $primaryKey = 'atid';
    public $timestamps = false;

    public $fillable = [
        'name',
        'icon',
        'date',
        'clientdate',
    ];
}
