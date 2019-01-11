<?php

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

    public static $statusMapping = [
        '1' => '申请中',
        '2' => '已分配',
        '3' => '已驳回'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }
}