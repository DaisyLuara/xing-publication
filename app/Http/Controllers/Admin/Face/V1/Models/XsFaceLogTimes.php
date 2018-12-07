<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/19
 * Time: 上午11:45
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

class XsFaceLogTimes extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_face_log_times';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}