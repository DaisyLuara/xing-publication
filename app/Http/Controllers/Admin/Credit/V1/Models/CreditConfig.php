<?php

namespace App\Http\Controllers\Admin\Credit\V1\Models;

use App\Models\ArModel;

/**
 * 分值配置
 * Class CreditConfig
 * @package App\Http\Controllers\Admin\Credit\V1\Models
 */
class CreditConfig extends ArModel
{
    protected $table = 'news_user_credits_config';

    protected $primaryKey = 'ccid';

    protected $fillable = [
        'title',//名称
        'sec',//密钥
        'sval',//分值区间
        'eval',//分值区间
        'nums',//次数
        'allval',//总分值
        'allnums',//不限制
        'pair',//匹配
        'result',//add:增加 reduce:减少
        'platform',//适用平台
        'role',//角色
        'type',//类型
        'mode',//模式
        'only',//唯一 0：否 1：是
        'state',//状态 0：下架 1：运营中
        'space',//间隔
        'shm',//开始小时
        'ehm',//结束小时
        'sdate',//自定义开始时间
        'edate',//自定义结束时间
        'date',
        'clientdate',
    ];

    public function credit_type()
    {
        return $this->belongsTo(CreditType::class, 'type', 'type');
    }

}
