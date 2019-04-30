<?php

namespace App\Http\Controllers\Admin\Credit\V1\Models;

use App\Http\Controllers\Admin\User\V1\Models\ArMember;
use App\Models\ArModel;

/**
 * 分值加减记录
 * Class CreditLog
 * @package App\Http\Controllers\Admin\Credit\V1\Models
 */
class CreditLog extends ArModel
{
    protected $table = 'news_user_credits_log';

    protected $fillable = [
        'uid',
        'ccid',
        'credits',
        'href',
        'ps',
        'date',
        'clientdate',
    ];

    public function credit_config(){
        return $this->belongsTo(CreditConfig::class ,'ccid','ccid');
    }

    public function ar_member(){
        return $this->belongsTo(ArMember::class ,'uid','uid');
    }

}
