<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 上午11:58
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property-read ContractCostKind $costKind
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereKindId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractCostContent whereUpdatedAt($value)
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

    public function costKind(): BelongsTo
    {
        return $this->belongsTo(ContractCostKind::class, 'cost_id', 'id');
    }
}