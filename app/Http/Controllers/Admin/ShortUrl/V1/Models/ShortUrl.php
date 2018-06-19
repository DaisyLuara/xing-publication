<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Models;

use App\Models\Model;

class ShortUrl extends Model
{
    public $fillable = [
        'target_url',
        'short_url',
        'source',
        'url_type',
        'scan_count',
        'tenant_id',
        'landing_record_id',
        'description',
        'channel'
    ];

    public function shortUrlRecords()
    {
        return $this->hasMany(PeopleViewRecords::class, 'short_url_id', 'id');
    }

}
