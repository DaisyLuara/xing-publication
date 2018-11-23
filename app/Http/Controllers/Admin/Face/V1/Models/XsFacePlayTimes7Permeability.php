<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/22
 * Time: 下午3:02
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

class XsFacePlayTimes7Permeability extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_face_playtimes7_permeability';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}