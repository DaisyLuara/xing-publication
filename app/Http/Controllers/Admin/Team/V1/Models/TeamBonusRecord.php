<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/28
 * Time: 下午5:49
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord
 *
 * @property int $id
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TeamBonusRecord extends Model
{
    protected $fillable=['date'];
}