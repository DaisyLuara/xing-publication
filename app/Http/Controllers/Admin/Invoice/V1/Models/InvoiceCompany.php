<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 上午10:09
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

class InvoiceCompany extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'taxpayer_num',
        'phone',
        'telephone',
        'address',
        'account_bank',
        'account_number'
    ];
}