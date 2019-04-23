<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 上午11:03
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Contract\V1\Models\ContractCost
 *
 * @property int $id
 * @property int $contract_id
 * @property int $applicant_id 所属人id
 * @property string $applicant_name 所属人
 * @property string $confirm_cost 已确认成本
 * @property string $total_cost 总成本
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract $contract
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent[] $costContent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereApplicantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereConfirmCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContractCost extends Model
{
    protected $fillable = [
        'contract_id',
        'applicant_id',
        'applicant_name',
        'confirm_cost',
        'total_cost'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function costContent()
    {
        return $this->hasMany(ContractCostContent::class, 'cost_id', 'id');
    }
}