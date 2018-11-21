<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/15
 * Time: 下午3:35
 */

namespace App\Http\Controllers\Admin\Common\V1\Models;


use App\Models\Model;

class WebsiteVisitor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'remark',
        'subscribe'
    ];
}