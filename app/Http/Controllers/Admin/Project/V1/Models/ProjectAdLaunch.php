<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Models\Model;

class ProjectAdLaunch extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_ad_list';
    protected $primaryKey = 'adid';
    public $timestamps = false;

    public $fillable = [
        'piid',
        'oid',
        'visiable',
        'type',
        'wiid',
        'url',
        'title',
        'image',
        'info',
        'reply',
        'e_title',
        'e_image',
        'e_info',
        'e_url',
        'only',
        'date',
        'clientdate'
    ];

    public function wxThird()
    {
        return $this->belongsTo(WxThird::class, 'wiid', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'piid', 'id');
    }
}
