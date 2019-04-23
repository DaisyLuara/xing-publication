<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/10
 * Time: 15:04
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount
 *
 * @property int $id
 * @property int $oid
 * @property string $belong
 * @property string $time
 * @property int $century00_bnum
 * @property int $century00_gnum
 * @property int $century90_bnum
 * @property int $century90_gnum
 * @property int $century80_bnum
 * @property int $century80_gnum
 * @property int $century70_bnum
 * @property int $century70_gnum
 * @property int $century10_bnum
 * @property int $century10_gnum
 * @property string|null $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury00Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury00Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury10Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury10Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury70Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury70Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury80Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury80Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury90Bnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereCentury90Gnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount whereTime($value)
 * @mixin \Eloquent
 */
class XsFaceCharacterCount extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_character_count';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }

}