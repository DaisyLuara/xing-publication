<?php

namespace App\Models;


class FaceCount extends Model
{
    protected $connection = 'ar';
    public $table = 'face_count_log';
    protected $primaryKey = 'fclid';

    public function point()
    {
        return $this->hasOne(Point::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'versionname', 'belong');
    }

}
