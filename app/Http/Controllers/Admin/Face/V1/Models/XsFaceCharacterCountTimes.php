<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/19
 * Time: 下午1:49
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

class XsFaceCharacterCountTimes extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_character_count_times';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }
}