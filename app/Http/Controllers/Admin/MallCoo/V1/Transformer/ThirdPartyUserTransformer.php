<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Transformer;

use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use League\Fractal\TransformerAbstract;

class ThirdPartyUserTransformer extends TransformerAbstract
{
    public function transform(ThirdPartyUser $thirdPartyUser)
    {
        return [
            'id' => $thirdPartyUser->id,
            'nickname' => $thirdPartyUser->nickname,
            'username' => $thirdPartyUser->username,
            'mallcoo_wx_open_id' => $thirdPartyUser->mallcoo_wx_open_id,
            'mobile' => $thirdPartyUser->mobile,
            'z' => $thirdPartyUser->z,
            'marketid' => $thirdPartyUser->marketid,
            'mallcoo_open_user_id' => $thirdPartyUser->mallcoo_open_user_id,
            'birthday' => $thirdPartyUser->birthday,
            'mall_card_apply_time' => $thirdPartyUser->mall_card_apply_time,
            'created_at' => $thirdPartyUser->created_at->toDateTimeString(),
            'updated_at' => $thirdPartyUser->updated_at->toDateTimeString(),
        ];
    }
}