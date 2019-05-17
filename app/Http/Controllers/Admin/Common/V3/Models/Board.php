<?php

namespace App\Http\Controllers\Admin\Common\V3\Models;

use App\Models\Model;

class Board extends Model
{

    protected $fillable = [
        'candidate_z',
        'belong',
        'oid',
        'image_url',
        'campaign',
        'candidate_mobile',
        'created_date',
    ];

}
