<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAdLaunch extends Model
{
    protected $connection='ar';
    public $table='ar_product_ad_list';
    protected $primaryKey='adid';

    public function wxThird(){
        return $this->belongsTo(WxThird::class,'wiid','id');
    }

    public function project(){
        return $this->belongsTo(Project::class,'piid','id');
    }
}
