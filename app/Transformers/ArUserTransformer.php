<?php

namespace App\Transformers;

use App\Models\ArUser;
use League\Fractal\TransformerAbstract;

class ArUserTransformer extends TransformerAbstract
{
    public function transform(ArUser $arUser)
    {
        return [
            'id' => (int)$arUser->uid,
            'name' => $arUser->realname,
        ];
    }

}