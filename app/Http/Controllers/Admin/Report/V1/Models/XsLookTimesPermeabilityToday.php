<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/6
 * Time: 下午5:01
 */

namespace App\Http\Controllers\Admin\Report\V1\Models;


use App\Models\Model;
use App\Scopes\MCExhibitionPointScope;

class XsLookTimesPermeabilityToday extends Model
{
    protected $table = 'xs_looktimes_permeability_today';

//    protected static function boot()
//    {
//        parent::boot();
//        static::addGlobalScope(new MCExhibitionPointScope());
//    }
}