<?php

namespace App\Transformers;

use App\Models\Point;
use League\Fractal\TransformerAbstract;

class PointTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['projects', 'arUsers', 'market'];

    public function transform(Point $point)
    {
        return [
            'id' => (int)$point->oid,
            'name' => $point->name,
            'info' => $point->info,
            'icon' => $point->icon,
            'image' => $point->image,
            'type' => $point->type,
        ];
    }

    public function includeProjects(Point $point)
    {
        return $this->collection($point->projects, new ProjectTransformer());
    }

    public function includeArUsers(Point $point)
    {
        return $this->collection($point->arUsers, new ArUserTransformer());
    }

    public function includeMarket(Point $point)
    {
        return $this->item($point->market, new MarketTransformer());
    }

}