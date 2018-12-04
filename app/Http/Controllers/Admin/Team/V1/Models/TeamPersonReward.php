<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:09
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

class TeamPersonReward extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'belong',
        'experience_money',
        'xo_money',
        'link_money',
        'system_money',
        'total',
        'date'
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
