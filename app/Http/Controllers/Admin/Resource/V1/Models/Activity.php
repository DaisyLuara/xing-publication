<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/30
 * Time: 下午3:20
 */

namespace App\Http\Controllers\Admin\Resource\V1\Models;

use App\Models\Model;

class Activity extends Model
{
    protected $filleable = ['name'];
}