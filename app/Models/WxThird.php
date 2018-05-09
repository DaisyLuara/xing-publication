<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxThird extends Model
{
    protected $connection='ar';
    public $table='wx_third_info';

    public function projectAdLaunch()
    {
        return $this->hasOne(ProjectAdLaunch::class,'wiid','id');
    }
}
