<?php

namespace App\Models;

use App\Scopes\FaceCountScope;

class FaceCount extends Model
{
    protected $connection = 'ar';
    public $table = 'face_count_log';
    protected $primaryKey = 'fclid';

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'versionname', 'belong');
    }

    public function pointArUser()
    {
        return $this->hasOne(PointArUser::class, 'oid', 'oid');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new FaceCountScope());
    }

}
