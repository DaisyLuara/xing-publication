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
    protected $table = 'week_ranking';
    protected $fillable = [
        'ar_user_id',
        'point_id',
        'looknum_average',
        'start_date',
        'end_date'
    ];
}