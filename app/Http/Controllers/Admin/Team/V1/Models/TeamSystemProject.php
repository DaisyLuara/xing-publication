<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:10
 */

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

class TeamSystemProject extends Model
{
    protected $fillable = [
        'name',
        'applicant',
        'status',
        'remark',
        'reject_message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }
}