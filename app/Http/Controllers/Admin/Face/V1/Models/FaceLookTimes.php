<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/19
 * Time: 下午3:25
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

class FaceLookTimes extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_face_log_times';

}