<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:41
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

class TeamRate extends Model
{
    protected $fillable = [
        'interaction',
        'originality',
        'h5_1',
        'h5_2',
        'animation',
        'plan',
        'tester',
        'operation'
    ];
}