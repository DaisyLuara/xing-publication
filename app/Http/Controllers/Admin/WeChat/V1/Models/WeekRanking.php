<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/31
 * Time: 17:44
 */

namespace App\Http\Controllers\Admin\WeChat\V1\Models;


use App\Models\Model;

class WeekRanking extends Model
{
    protected $fillable = [
        'ar_user_id',
        'ar_user_name',
        'point_id',
        'point_name',
        'scene_id',
        'scene_name',
        'looknum_average',
        'ranking',
        'start_date',
        'end_date',
        'date'
    ];
}