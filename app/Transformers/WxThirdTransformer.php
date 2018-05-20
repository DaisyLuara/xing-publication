<?php

namespace App\Transformers;


use App\Models\WxThird;
use League\Fractal\TransformerAbstract;

class WxThirdTransformer extends TransformerAbstract
{
    public function transform(WxThird $wxThird)
    {
        return [
            'id' => $wxThird->id,
            'appid' => $wxThird->appid,
            'name' => $wxThird->nick_name,
            'icon' => $wxThird->head_img,
            'type' => $wxThird->projectAdLaunch->type,
            'expires' => $wxThird->expires_in,
            'created_at' => $wxThird->date,
            'updated_at' => formatClientDate($wxThird->clientdate),
        ];
    }
}