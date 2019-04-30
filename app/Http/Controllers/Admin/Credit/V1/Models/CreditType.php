<?php

namespace App\Http\Controllers\Admin\Credit\V1\Models;

use App\Models\ArModel;

/**
 * 分值类型
 * Class CreditType
 * @package App\Http\Controllers\Admin\Credit\V1\Models
 */
class CreditType extends ArModel
{
    protected $table = 'news_user_credits_type';

    protected $primaryKey = 'ctid';

    protected $fillable = [
        'name',
        'ps',
        'icon',
        'date',
        'clientdate'
    ];

}
