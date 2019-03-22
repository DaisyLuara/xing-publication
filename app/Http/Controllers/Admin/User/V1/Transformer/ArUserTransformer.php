<?php

namespace App\Http\Controllers\Admin\User\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use League\Fractal\TransformerAbstract;

class ArUserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['points'];

    public function transform(ArUser $arUser)
    {
        return [
            'id' => (int)$arUser->uid,
            'z' => $arUser->z,
            'name' => $arUser->realname,
        ];
    }

    public function includePoints(ArUser $arUser)
    {
        return $this->collection($arUser->points, new PointTransformer());
    }

}