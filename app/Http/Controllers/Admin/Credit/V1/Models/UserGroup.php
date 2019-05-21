<?php

namespace App\Http\Controllers\Admin\Credit\V1\Models;

use App\Models\ArModel;

/**
 * 用户分值等级
 * Class UserGroup
 * @package App\Http\Controllers\Admin\Credit\V1\Models
 */
class UserGroup extends ArModel
{
    protected $table = 'news_usergroups';

    protected $primaryKey = 'groupid';

    protected $fillable = [
        'groupname', //名称
        'creditslow', //积分低
        'creditshigh', //积分高
        'icon',
        'role',//角色 normal:通用
        'type',//类型 credits 积分；rep 信誉；rmb 充值；hd 嗨豆；
        'stars',//等级
        'allowavatar',//	允许自定义头像
        'maxpmnum',//最大消息数量
        'maxsigsize',//	最大关注数量
        'date',//
        'clientdate',//
    ];

}
