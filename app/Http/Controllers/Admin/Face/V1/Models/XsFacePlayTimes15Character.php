<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/22
 * Time: 下午2:59
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

class XsFacePlayTimes15Character extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_face_playtimes15_character_count';
}