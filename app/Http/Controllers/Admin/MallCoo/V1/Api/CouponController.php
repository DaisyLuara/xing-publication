<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CouponController extends Controller
{

    /**
     * 获取该商场下优惠分类列表
     */
    public function index()
    {
        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/PutIn/v1/GetAll/';
        $result = $mall_coo->send($sUrl);
        abort_if($result['Code'] != 1, 500, $result['Message']);
        return response()->json($result['Data'], 'Get Coupons Success!');

    }


    /**
     * 发送优惠券
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'open_user_id' => 'required',
            'pic_mid' => 'required',
        ]);

        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/v1/Send/ByOpenUserID/';

        /**
         * @todo 根据封装成 可以根据手机号码 微信id 发送的形式
         */
        $data = [
            'UserList' => [
                [
                    'BussinessID' => null,
                    'TraceID' => uniqid() . config('mall_coo.app_id'),
                    'PICMID' => $request->get('pic_mid'),
                    'OpenUserID' => $request->get('open_user_id'),
                ]
            ]
        ];

        $result = $mall_coo->send($sUrl, $data);
        abort_if($result['Code'] != 1, 500, $result['Message']);

        /**
         * mallcoo 本身提供的是批量接口
         * 目前业务需要 每次只发送一张优惠券
         */
        if (!$result['Data'][0]['IsSuccess']) {
            abort(500, $result['Data'][0]['FailReason']);
        }

        return response()->json($result['Data'][0], 'Send Coupon Success!');

    }

}
