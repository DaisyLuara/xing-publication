<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;

class ProjectPermission extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_per_pid';
    public $timestamps = false;

    public function projects()
    {
        return $this->belongsTo(Project::class, 'pid', 'pid');
    }
}
