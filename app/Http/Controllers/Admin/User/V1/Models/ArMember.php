<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Models\Model;

class ArMember extends Model
{
    protected $connection = 'ar';
    public $table = 'news_members';
    protected $primaryKey = 'uid';
}
