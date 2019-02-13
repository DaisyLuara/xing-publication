<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 上午11:03
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

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