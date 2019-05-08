<?php

namespace App\Http\Controllers\Admin\Credit\V1\Transformer;

use App\Http\Controllers\Admin\Credit\V1\Models\CreditConfig;
use League\Fractal\TransformerAbstract;

class CreditConfigTransformer extends TransformerAbstract
{

    public function transform(CreditConfig $creditConfig): array
    {
        return [
            'ccid' => $creditConfig->ccid,
            'title' => $creditConfig->title,//名称
            'sec' => $creditConfig->sec,//密钥
            'sval' => $creditConfig->sval,//分值区间
            'eval' => $creditConfig->eval,//分值区间
            'nums' => $creditConfig->nums,//次数
            'allval' => $creditConfig->allval,//总分值
            'allnums' => $creditConfig->allnums,//不限制
            'pair' => $creditConfig->pair,//匹配
            'result' => $creditConfig->result,//add:增加 reduce:减少
            'platform' => $creditConfig->platform,//适用平台
            'role' => $creditConfig->role,//角色
            'type' => $creditConfig->type,//类型
            'type_text' => $creditConfig->credit_type ? $creditConfig->credit_type->name : '未知',
            'mode' => $creditConfig->mode,//模式
            'only' => $creditConfig->only,//唯一 0：否 1：是
            'state' => $creditConfig->state,//状态 0：下架 1：运营中
            'space' => $creditConfig->space,//间隔
            'shm' => $creditConfig->shm,//开始小时
            'ehm' => $creditConfig->ehm,//结束小时
            'sdate' => $creditConfig->sdate,//自定义开始时间
            'edate' => $creditConfig->edate,//自定义结束时间
        ];
    }
}