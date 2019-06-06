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
 * @property string|null $alias
 * @property string $name
 * @property int $default_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostKind newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostKind newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostKind query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostKind whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostKind whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostKind whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostKind whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContractCostKind extends Model
{
    protected $fillable = ['alias', 'name', 'default_cost'];
}