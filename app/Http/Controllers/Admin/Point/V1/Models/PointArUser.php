<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Point\V1\Models\PointArUser
 *
 * @property int $id
 * @property int $uid
 * @property string $z 用户标识
 * @property int $marketid 商场ID
 * @property string|null $oids 点位集合
 * @property string $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser whereOids($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointArUser whereZ($value)
 * @mixin \Eloquent
 */
class PointArUser extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_per_oid';
}
