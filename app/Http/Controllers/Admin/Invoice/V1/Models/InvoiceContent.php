<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/16
 * Time: 下午1:34
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

class InvoiceContent extends Model
{
    protected $fillable=[
        'invoice_id',
        'name',
        'spec_type',
        'unit',
        'num',
        'price',
        'total'
    ];
    public $timestamps=false;
}