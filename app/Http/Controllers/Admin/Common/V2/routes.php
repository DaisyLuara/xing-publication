<?php
$api->version('v2', [
    'namespace' => 'App\Http\Controllers\Admin\Common\V2\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        //h5页面优惠券
        $api->group(['middleware' => 'api_sign'], function ($api) {

            $api->post('open/coupons/{couponBatch}', 'CouponController@generateCoupon');//发送优惠券
            $api->post('open/user/coupon', 'CouponController@getUserCoupon');//获取用户优惠券

            $api->any('open/coupon/batches', 'CouponController@generateCouponBatch');//按策略获取优惠券规则详情
            $api->any('open/coupon/batches/limit', 'CouponController@generateLimitCouponBatch');//h5获取有限制条件的优惠券规则详情
            $api->post('open/projects/coupons', 'CouponController@generateMultiProjectsCoupon');//h5独立发送优惠券
        });

    });

});