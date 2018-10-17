<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/13
 * Time: 上午11:44
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

class XsFaceLog extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_log';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}