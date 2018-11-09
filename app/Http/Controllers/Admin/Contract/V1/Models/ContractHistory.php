<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午10:33
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Models\Model;

class ContractHistory extends Model
{
    protected $fillable = [
        'user_id',
        'contract_id'
    ];
}