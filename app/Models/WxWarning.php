<?php

namespace App\Models;

use Eloquent as Model;

class WxWarning extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'message',
        'oid',
        'type',
        'project',
        'push_id',
        'reason',
        'address',
        'product_id',
        'market_id',
    ];
}
