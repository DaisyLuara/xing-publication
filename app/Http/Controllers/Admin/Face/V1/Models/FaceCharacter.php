<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/7
 * Time: 17:58
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

class FaceCharacter extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_character';
}