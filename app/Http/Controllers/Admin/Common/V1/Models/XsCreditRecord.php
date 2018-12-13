<?php

namespace App\Http\Controllers\Admin\Common\V1\Models;

use App\Models\Model;

class XsCreditRecord extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_credit_records';
    protected $fillable = ['uid', 'num', 'key'];

}
