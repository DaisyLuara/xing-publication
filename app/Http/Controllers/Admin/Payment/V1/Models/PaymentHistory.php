<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 下午3:24
 */

namespace App\Http\Controllers\Admin\Payment\V1\Models;


use App\Models\Model;

class PaymentHistory extends Model
{
    protected $fillable = [
        'user_id',
        'payment_id'
    ];
}