<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\Model;

class Activity extends Model
{
    protected $connection = 'ar';
    protected $table = 'avr_activity';
    protected $primaryKey = 'acid';
    public $timestamps = false;

    public $fillable = [
        'acid',
        'cid',
        'title',
        'txt',
        'tabs',
        'image',
        'video',
        'loc',
        'gps',
        'awardkey',
        'oid',
        'info',
        'infolink',
        'ps',
        'pslink',
        'date',
        'clientdate',
    ];


}
