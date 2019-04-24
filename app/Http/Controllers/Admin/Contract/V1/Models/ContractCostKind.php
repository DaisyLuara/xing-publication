<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午1:34
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContractCostKind extends Model
{
    protected $fillable = ['name'];
}