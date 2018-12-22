<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;

class UserActivation extends ArModel
{
    protected $table = 'new_user_oid';

    protected $fillable = [
        'uid',
        'areaid',
        'marketid',
        'oid',
    ];
}

