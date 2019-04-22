<?php

namespace App\Http\Controllers\Admin\Guest\V1\Transformer;


use App\Http\Controllers\Admin\Guest\V1\Models\GuestMobile;
use League\Fractal\TransformerAbstract;

class GuestMobileTransformer extends TransformerAbstract
{

    public function transform(GuestMobile $guestMobile) :array
    {
        return [
            'id' => $guestMobile->id,
            'mobile' => $guestMobile->mobile,
            'ip' => $guestMobile->ip,
            'city' => $guestMobile->city,
            'created_at' => (string)$guestMobile->created_at,
            'updated_at' => (string)$guestMobile->updated_at

        ];
    }
}