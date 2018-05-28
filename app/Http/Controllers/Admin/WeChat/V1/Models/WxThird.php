<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch;
use App\Models\Model;

class WxThird extends Model
{
    protected $connection = 'ar';
    public $table = 'wx_third_info';

    public function projectAdLaunch()
    {
        return $this->hasOne(ProjectAdLaunch::class, 'wiid', 'id');
    }
}
