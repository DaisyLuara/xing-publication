<?php

namespace App\Http\Controllers\Admin\Credit\V1\Transformer;

use App\Http\Controllers\Admin\Credit\V1\Models\UserGroup;
use League\Fractal\TransformerAbstract;

class UserGroupTransformer extends TransformerAbstract
{
    public function transform(UserGroup $userGroup)
    {
        return [
            'groupid' => $userGroup->groupid,
            'groupname' => $userGroup->groupname, //名称
            'creditslow' => $userGroup->creditslow, //积分低
            'creditshigh' => $userGroup->creditshigh, //积分高
            'icon' => $userGroup->icon,
            'role' => $userGroup->role,//角色 normal:通用
            'type' => $userGroup->type,//类型 credits 积分；rep 信誉；rmb 充值；hd 嗨豆；
            'stars' => $userGroup->stars,//等级
            'allowavatar' => $userGroup->allowavatar,//	允许自定义头像
            'maxpmnum' => $userGroup->maxpmnum,//最大消息数量
            'maxsigsize' => $userGroup->maxsigsize,//	最大关注数量
        ];
    }
}