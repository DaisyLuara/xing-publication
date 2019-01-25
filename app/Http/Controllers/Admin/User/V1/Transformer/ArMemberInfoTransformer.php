<?php

namespace App\Http\Controllers\Admin\User\V1\Transformer;

use App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo;
use League\Fractal\TransformerAbstract;

class ArMemberInfoTransformer extends TransformerAbstract
{

    public function transform(ArMemberInfo $arMemberInfo)
    {
        return [
            'uid' => (int)$arMemberInfo->uid,
            'avatar' => $arMemberInfo->face,
        ];
    }


}