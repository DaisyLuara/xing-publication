<?php

namespace App\Http\Controllers\Admin\Board\V1\Models;

use App\Http\Controllers\Resource\V1\Models\ActivityMedia;
use App\Models\Model;

class Board extends Model
{

    protected $fillable = [
        'candidate_z',
        'candidate',
        'message',
        'belong',
        'oid',
        'image_url',
        'campaign',
        'candidate_mobile',
        'created_date',
        'activity_media_id',
        'headimageurl',
    ];

}
