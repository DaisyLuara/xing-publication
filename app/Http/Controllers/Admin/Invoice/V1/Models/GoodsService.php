<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/23
 * Time: 下午6:20
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

class GoodsService extends Model
{
    protected $fillable = [
        'id',
        'name',
        'spec_type',
        'unit'
    ];
}