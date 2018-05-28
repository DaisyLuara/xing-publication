<?php

namespace App\Http\Controllers\Admin\Device\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

class Push extends Model
{
    protected $connection = 'ar';
    public $table = 'push';

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'alias', 'versionname');
    }
}
