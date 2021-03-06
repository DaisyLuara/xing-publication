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

            $api->post('arMember/verificationCodes', 'ArMemberController@sendVerificationCodes'); // 短信验证码
            $api->patch('user', 'ArMemberController@update');//绑定手机号

            //告白类通用接口
            $api->post('confessions', 'ConfessionsController@store');//上传告白
            $api->get('user/confession', 'ConfessionsController@show');//查询告白
            $api->patch('user/confession', 'ConfessionsController@update');//更新告白
            $api->get('user/upload/confession', 'ConfessionsController@extract');//提取告白
            $api->get('confessions', 'ConfessionsController@index');//告白墙列表
        });

    });

});