<?php

namespace App\Transformers;

use App\Models\ArUser;
use League\Fractal\TransformerAbstract;

class ArUserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['points'];

    public function transform(ArUser $arUser)
    {
        return [
            'id' => (int)$arUser->uid,
            'name' => $arUser->realname,
        ];
    }

    public function includePoints(ArUser $arUser)
    {
        return $this->collection($arUser->points, new PointTransformer());
    }

}