<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午10:33
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Contract\V1\Models\ContractHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $contract_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractHistory whereUserId($value)
 * @mixin \Eloquent
 */
class ContractHistory extends Model
{
    protected $fillable = [
        'user_id',
        'contract_id'
    ];
}