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
        });

    });

});