<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Point;
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
        'clientdate'
    ];

    public function points()
    {
        return $this->belongsToMany(Point::class, 'istar_tv_oid', 'default_plid', 'oid');
    }
}