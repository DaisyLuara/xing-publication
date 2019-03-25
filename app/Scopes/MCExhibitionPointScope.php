<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/15
 * Time: 下午1:39
 */

namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class MCExhibitionPointScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();
        $builder->whereIn("$table.oid", [739, 740, 741, 742, 743, 744, 746, 747, 748]);
    }
}