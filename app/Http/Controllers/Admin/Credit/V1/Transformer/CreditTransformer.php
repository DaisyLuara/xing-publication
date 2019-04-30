<?php

namespace App\Http\Controllers\Admin\Credit\V1\Transformer;

use App\Http\Controllers\Admin\Credit\V1\Models\Credit;
use League\Fractal\TransformerAbstract;

class CreditTransformer extends TransformerAbstract
{
    public function transform(Credit $credit): array
    {
        return [
            'username' => $credit->customer ? $credit->customer->name : $credit->username,
            'mobile' => $credit->mobile,
            'p_groupid' => $credit->p_groupid,
            'group_name' => $credit->user_group->groupname ,
            'group_icon' => $credit->user_group ? $credit->user_group->icon : 'http://image.xingstation.cn/1007/image/1556162610.png',
            'p_credits' => $credit->p_credits,
            'p_money' => $credit->p_money,
            'p_rmb' => $credit->p_rmb,
            'p_rep' => $credit->p_rep,
        ];
    }
}