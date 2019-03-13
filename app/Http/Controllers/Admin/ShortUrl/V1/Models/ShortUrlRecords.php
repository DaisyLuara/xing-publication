<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Models;

use App\Models\Model;

class ShortUrlRecords extends Model
{
    public $fillable = [
        'short_url_id',
        'utm_source',
        'utm_medium',
        'utm_term',
        'utm_campaign',
        'utm_content',
        'ua',
        'ip',
        'face_id',
        'browser',
        'platform',
        'device',
        'app',
        'third_id',
        'clientdate'
    ];
}
