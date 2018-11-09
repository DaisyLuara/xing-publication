<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/24
 * Time: 上午11:44
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

class ContractReceiveDate extends Model
{
    protected $fillable = [
        'contract_id',
        'receive_date',
        'receive_status'
    ];
    public $timestamps = false;

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
}