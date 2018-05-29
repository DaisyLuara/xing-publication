<?php

namespace App\Http\Controllers\Admin\Face\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

class FaceCollect extends Model
{
    protected $connection = 'ar';
    public $table = 'face_collect';
    protected $primaryKey = 'fcid';

    public function point()
    {
        return $this->hasOne(Point::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'versionname', 'belong');
    }

}
