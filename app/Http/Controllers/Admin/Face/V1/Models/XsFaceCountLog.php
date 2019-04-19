<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/9
 * Time: 19:52
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;

use App\Models\Model;
use App\Scopes\ExceptPointsScope;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog
 *
 * @property int $id
 * @property int $oid
 * @property string $belong
 * @property int $looknum
 * @property int $playernum7
 * @property int $playernum15
 * @property int $playernum21
 * @property int $playernum
 * @property int $outnum
 * @property int $scannum
 * @property int $omo_outnum
 * @property int $omo_scannum
 * @property int $omo_sharenum
 * @property int $lovenum
 * @property int $phonenum
 * @property int $oanum
 * @property int $phonetimes
 * @property int $oatimes
 * @property int $looktimes
 * @property int $playtimes7
 * @property int $playtimes15
 * @property int $playtimes21
 * @property int $lovetimes
 * @property int $coupontimes
 * @property int $verifytimes
 * @property string|null $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereCoupontimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereLooknum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereLooktimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereLovenum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereLovetimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereOanum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereOatimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereOmoOutnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereOmoScannum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereOmoSharenum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereOutnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePhonenum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePhonetimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePlayernum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePlayernum15($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePlayernum21($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePlayernum7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePlaytimes15($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePlaytimes21($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog wherePlaytimes7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereScannum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog whereVerifytimes($value)
 * @mixin \Eloquent
 */
class XsFaceCountLog extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_count_log';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }

}