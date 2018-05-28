<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;


class ProjectAdLog extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_ad_log';

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function wxThird()
    {
        return $this->belongsTo(WxThird::class, 'wiid', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'piid', 'id');
    }
}