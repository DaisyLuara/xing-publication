<?php

namespace App\Http\Controllers\Admin\Face\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\PointArUser;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Scopes\ExceptPointsScope;
use App\Models\Model;

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
        static::addGlobalScope(new ExceptPointsScope());
    }

}
