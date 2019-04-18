<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/22
 * Time: 下午3:02
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability
 *
 * @property int $id
 * @property int $oid
 * @property string $belong
 * @property int $bnum
 * @property int $gnum
 * @property int $age10b
 * @property int $age10g
 * @property int $age18b
 * @property int $age18g
 * @property int $age30b
 * @property int $age30g
 * @property int $age40b
 * @property int $age40g
 * @property int $age60b
 * @property int $age60g
 * @property int $age61b
 * @property int $age61g
 * @property string|null $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge10b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge10g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge18b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge18g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge30b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge30g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge40b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge40g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge60b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge60g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge61b($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereAge61g($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereBnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereGnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFacePlayTimes15Permeability whereOid($value)
 * @mixin \Eloquent
 */
class XsFacePlayTimes15Permeability extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_face_playtimes15_permeability';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}