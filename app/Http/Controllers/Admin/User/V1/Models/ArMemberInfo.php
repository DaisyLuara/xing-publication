<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Models\Model;

class ArMemberInfo extends Model
{
    protected $connection = 'ar';
    public $table = 'news_memberfields';
    protected $primaryKey = 'uid';
}
