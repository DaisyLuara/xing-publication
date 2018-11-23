<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/22
 * Time: 上午11:15
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

class FacePermeabilityRecord extends Model
{
    //7s,15s,21s用户渗透率清洗记录表
    protected $fillable = ['date'];
}