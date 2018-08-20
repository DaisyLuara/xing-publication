<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExceptPointsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();
        $builder->whereNotIn("$table.oid", [16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335, 540]);
    }
}