<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Models\Model;

class PlayingType extends Model
{
    protected $connection = 'ar';
    protected $table = 'ar_award_type';
    protected $primaryKey = 'aid';
    public $timestamps = false;

}
