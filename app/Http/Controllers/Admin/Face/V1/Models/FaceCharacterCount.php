<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/10
 * Time: 15:04
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;
use App\Scopes\ExceptPointsScope;

class FaceCharacterCount extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_face_character_count';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ExceptPointsScope());
    }

}