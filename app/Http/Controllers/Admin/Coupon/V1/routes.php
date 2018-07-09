<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Coupon\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            $api->get('coupon/policies', 'PolicyController@index');
            $api->post('coupon/policy', 'PolicyController@store');
            $api->patch('coupon/policy', 'PolicyController@update');

            $api->get('coupon/batches', 'CouponBatchController@index');
            $api->post('coupon/batch', 'CouponBatchController@store');
            $api->patch('coupon/batch', 'CouponBatchController@update');

            $api->post('policy/{policy}/batch/{couponBatch}', 'PolicyController@storeBatchPolicy');
            $api->patch('policy/{policy}/batch/{id}', 'PolicyController@updateBatchPolicy');
        });


    });

});