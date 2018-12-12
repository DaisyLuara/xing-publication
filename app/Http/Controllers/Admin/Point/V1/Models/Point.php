<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Models\ArModel;

class Point extends ArModel
{

    public $table = 'avr_official';
    protected $primaryKey = 'oid';

    protected $fillable = [
        'areaid', 'marketid', 'sid', 'bd_uid', 'site_uid',
        'hours', 'shours', 'ehours', 'weekday', 'weekend',
        'visiable', 'name', 'info', 'icon', 'type', 'date',
        'clientdate', 'lat', 'lng', 'geo_hash'
    ];

    public function arUsers()
    {
        return $this->belongsTo(ArUser::class, 'oid', 'bd_uid');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'istar_tv_oid', 'oid', 'default_plid');
    }

    public function scene()
    {
        return $this->belongsTo(Scene::class, 'sid', 'sid');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function share()
    {
        return $this->hasOne(PointShare::class, 'oid', 'oid');
    }

    public function contract()
    {
        return $this->hasOne(PointContract::class, 'oid', 'oid');
    }

    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'xs_point_attributes', 'point_id', 'attribute_id')->where('parent_id', '=', '5');
    }

}
