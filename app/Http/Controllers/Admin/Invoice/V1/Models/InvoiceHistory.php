<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午10:47
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

class InvoiceHistory extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id'
    ];
}