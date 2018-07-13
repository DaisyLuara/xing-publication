<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/10
 * Time: 15:04
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

class FaceCharacterCount extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_character_count';
}