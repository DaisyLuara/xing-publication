<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\MallCoo\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->group(['prefix' => 'mallcoo'], function ($api) {

            $api->post('verificationCodes', 'VerificationCodesController@store'); // 短信验证码
            $api->post('huiju/verificationCodes', 'VerificationCodesController@sendVerificationCodes'); // 短信验证码
            $api->post('couponMessage', 'VerificationCodesController@sendCouponMessage'); // 发送优惠券码

            $api->post('user/oauth', 'UserController@oauth');//获取授权页面url
            $api->any('user/callback', 'UserController@callback');//授权回调

            $api->post('users', 'UserController@store'); //手机号开通会员卡
            $api->get('user', 'UserController@show');//获取商场会员信息

            $api->get('user/coupon', 'CouponController@show');//获取用户优惠券
            $api->post('coupons', 'CouponController@store');//发送优惠券

            $api->get('couponPacks', 'CouponController@getUserCouponPacks');//查看优惠券包
            $api->post('couponPacks', 'CouponController@generateCouponPacks');//发送优惠券包

        });
    });

});