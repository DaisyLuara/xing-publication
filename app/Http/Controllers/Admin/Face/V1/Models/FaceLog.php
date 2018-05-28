<?php

namespace App\Http\Controllers\Admin\Face\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\PointArUser;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Scopes\ExceptPointsScope;
use App\Models\Model;

class FaceLog extends Model
{
    protected $connection = 'ar';
    public $table = 'face_log';
    protected $primaryKey = 'flid';

    public function pointArUser()
    {
        return $this->hasOne(PointArUser::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'versionname', 'belong');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}
