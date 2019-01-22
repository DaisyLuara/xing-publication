<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Models\Model;

class ActivityParticipant extends Model
{
    protected $connection = 'ar';
    protected $table = 'ar_award_list';
    protected $primaryKey = 'auid';
    public $timestamps = false;

}
