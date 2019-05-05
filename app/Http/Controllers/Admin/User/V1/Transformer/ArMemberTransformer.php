<?php

namespace App\Http\Controllers\Admin\User\V1\Transformer;

use App\Http\Controllers\Admin\User\V1\Models\ArMember;
use League\Fractal\TransformerAbstract;

class ArMemberTransformer extends TransformerAbstract
{

    public function transform(ArMember $arMember)
    {
        return [
            'username' => $arMember->username,
            'mobile' => $arMember->mobile,
        ];
    }


}