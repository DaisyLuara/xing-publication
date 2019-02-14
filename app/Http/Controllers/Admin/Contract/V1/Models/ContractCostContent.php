<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 上午11:58
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

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