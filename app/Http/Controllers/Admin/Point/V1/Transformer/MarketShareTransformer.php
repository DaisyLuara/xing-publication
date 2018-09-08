<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\MarketShare;
use League\Fractal\TransformerAbstract;

class MarketShareTransformer extends TransformerAbstract
{

    public function transform(MarketShare $marketShare)
    {
        return [
            'marketid' => $marketShare->marketid,
            'site' => $marketShare->site,
            'vipad' => $marketShare->vipad,
            'ad' => $marketShare->ad,
            'agent' => $marketShare->agent,
            'offer' => $marketShare->offer,
            'offer_off' => $marketShare->offer_off,
            'mad' => $marketShare->mad,
            'mad_off' => $marketShare->mad_off,
            'play' => $marketShare->play,
            'play_off' => $marketShare->play_off,
            'qrcode' => $marketShare->qrcode,
            'qrcode_off' => $marketShare->qrcode_off,
            'wx_pa' => $marketShare->wx_pa,
            'wx_pa_off' => $marketShare->wx_pa_off,
            'wx_mp' => $marketShare->wx_mp,
            'wx_mp_off' => $marketShare->wx_mp_off,
            'app' => $marketShare->app,
            'app_off' => $marketShare->app_off,
            'phone' => $marketShare->phone,
            'phone_off' => $marketShare->phone_off,
            'coupon' => $marketShare->coupon,
            'coupon_off' => $marketShare->coupon_off,
            'date' => $marketShare->date,
        ];
    }
}