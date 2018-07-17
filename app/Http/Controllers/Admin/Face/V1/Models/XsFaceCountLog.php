<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/9
 * Time: 19:52
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;

use App\Models\Model;
use App\Scopes\ExceptPointsScope;

class XsFaceCountLog extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_count_log';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }

}