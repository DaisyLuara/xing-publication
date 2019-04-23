<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:31
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamBonus
 *
 * @property int $id
 * @property string $project_name
 * @property string $belong
 * @property string $money 奖金
 * @property string|null $date
 * @property string $factor 系数
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus whereFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamBonus whereProjectName($value)
 * @mixin \Eloquent
 */
class TeamBonus extends Model
{

}