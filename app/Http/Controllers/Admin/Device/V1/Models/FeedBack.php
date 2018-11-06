<?php

namespace App\Http\Controllers\Admin\Device\V1\Models;

use App\Models\Model;

class FeedBack extends Model
{
    protected $connection = 'xs';
    public $table = 'game_feedback';

    protected $fillable = [
        'op_time',
        'coupon_id',
        'code',
        'action',
        'created_at',
        'updated_at',
        'game_name',
        'device_code',
        'user_nick',
    ];
}
