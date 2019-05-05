<?php
$api->version('v3', [
    'namespace' => 'App\Http\Controllers\Admin\Common\V3\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->group(['middleware' => 'api_sign'], function ($api) {
            $api->get('device/coupon/batches', 'CouponBatchController@show');

            $api->post('open/coupons', 'CouponController@store');//发送优惠券
            $api->post('open/user/coupon', 'CouponController@getUserCoupon');//获取用户优惠券
            $api->get('open/coupon/batches', 'CouponBatchController@store');//h5策略抽奖

            $api->post('verificationCodes', 'ArMemberController@sendVerificationCodes'); // 短信验证码
            $api->patch('user', 'ArMemberController@update');//绑定手机号

            $api->post('confessions', 'ConfessionsController@store');//上传全城告白
            $api->get('user/confession', 'ConfessionsController@show');//查询全城告白
        });

    });

});