<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 下午2:27
 */

namespace App\Http\Controllers\Admin\Payment\V1\Models;


use App\Models\Model;

class PaymentPayee extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'account_bank',
        'account_number',
    ];
}