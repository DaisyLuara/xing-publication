<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\PointShare;
use League\Fractal\TransformerAbstract;

class PointShareTransformer extends TransformerAbstract
{

    public function transform(PointShare $pointShare)
    {
        return [
            'oid' => $pointShare->oid,
            'site' => $pointShare->site,
            'vipad' => $pointShare->vipad,
            'ad' => $pointShare->ad,
            'agent' => $pointShare->agent,
            'offer' => $pointShare->offer,
            'offer_off' => $pointShare->offer_off,
            'mad' => $pointShare->mad,
            'mad_off' => $pointShare->mad_off,
            'play' => $pointShare->play,
            'play_off' => $pointShare->play_off,
            'qrcode' => $pointShare->qrcode,
            'qrcode_off' => $pointShare->qrcode_off,
            'wx_pa' => $pointShare->wx_pa,
            'wx_pa_off' => $pointShare->wx_pa_off,
            'wx_mp' => $pointShare->wx_mp,
            'wx_mp_off' => $pointShare->wx_mp_off,
            'app' => $pointShare->app,
            'app_off' => $pointShare->app_off,
            'phone' => $pointShare->phone,
            'phone_off' => $pointShare->phone_off,
            'coupon' => $pointShare->coupon,
            'coupon_off' => $pointShare->coupon_off,
            'date' => $pointShare->date,
        ];
    }
}