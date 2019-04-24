<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 上午11:58
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent
 *
 * @property int $id
 * @property int $cost_id 成本id
 * @property int $creator_id 创建人id
 * @property string $creator 创建人
 * @property int $kind_id 成本类型
 * @property string $money 成本金额
 * @property string|null $remark 备注
 * @property int $status 0:未确认,1:已确认
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereKindId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContractCostContent extends Model
{
    protected $fillable = [
        'cost_id',
        'creator_id',
        'creator',
        'kind_id',
        'money',
        'remark',
        'status',
    ];
}