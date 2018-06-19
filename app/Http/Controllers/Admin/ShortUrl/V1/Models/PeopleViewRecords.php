<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Models;

use App\Models\Model;

class PeopleViewRecords extends Model
{
    protected $connection = 'ar';
    public $table = 'file_upload';
    public $timestamps = false;

    public $fillable = [
        'share',
        'oid'
    ];
}
