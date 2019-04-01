<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/6
 * Time: 下午2:25
 */

namespace App\Http\Controllers\Admin\Report\V1\Models;


use App\Models\Model;
use App\Scopes\MCExhibitionPointScope;

class XsFaceCountToday extends Model
{
    protected $table = 'xs_face_count_today';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MCExhibitionPointScope());
    }
}