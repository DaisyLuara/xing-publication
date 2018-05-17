<?php

namespace App\Models;

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
