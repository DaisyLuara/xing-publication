<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/22
 * Time: 上午10:18
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

class FacePlayCharacterRecord extends Model
{
    //7s,15s,21s人群特征清洗记录表
    protected $fillable = ['date'];
}