<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:09
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

class TeamPersonReward extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'belong',
        'money',
        'date'
    ];
    public $timestamps = false;
}