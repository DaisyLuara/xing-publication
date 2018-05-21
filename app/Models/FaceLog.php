<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ExceptPointsScope;

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
