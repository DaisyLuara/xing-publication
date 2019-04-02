<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Models\Model;

class ArMemberPermission extends Model
{
    protected $connection = 'ar';
    public $table = 'news_user_permission';
    protected $primaryKey = 'uid';
}
