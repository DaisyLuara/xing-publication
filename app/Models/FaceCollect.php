<?php

namespace App\Models;


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
