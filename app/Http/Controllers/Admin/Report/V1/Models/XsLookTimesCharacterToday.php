<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/7
 * Time: 上午10:59
 */

namespace App\Http\Controllers\Admin\Report\V1\Models;


use App\Models\Model;
use App\Scopes\MCExhibitionPointScope;

class XsLookTimesCharacterToday extends Model
{
    protected $table = "xs_looktimes_character_today";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MCExhibitionPointScope());
    }
}