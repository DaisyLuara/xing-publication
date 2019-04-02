<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Company\V1\Models\CompanyProject;
use App\Models\Model;

class Project extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_list';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'tid',
        'name',
        'info',
        'icon',
        'image',
        'link',
        'size',
        'packname',
        'versioncode',
        'versionname',
        'open',
        'scan',
        'linkall',
        'date',
        'clientdate',
        'policy_id',
    ];

    public function points()
    {
        return $this->belongsToMany(Point::class, 'istar_tv_oid', 'default_plid', 'oid');
    }

    public function policy()
    {
        return $this->setConnection('mysql')->hasOne(Policy::class, 'id', 'policy_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProject::class, 'id', 'project_id');
    }

    public function permissions()
    {
        return $this->hasMany(ProjectPermission::class, 'pid', 'id');
    }

}
