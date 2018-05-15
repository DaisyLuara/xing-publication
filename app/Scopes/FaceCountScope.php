<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/15
 * Time: 15:59
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FaceCountScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327]);
    }
}