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

            $api->get('coupon/policies/{policy}', 'PolicyController@show');
            $api->get('coupon/policies', 'PolicyController@index');
            $api->post('company/{company}/coupon/policy', 'PolicyController@store');
            $api->patch('coupon/policy/{policy}', 'PolicyController@update');

            $api->post('policy/{policy}', 'PolicyController@storeBatchPolicy');
            $api->patch('policy/{policy}/batch_policy/{batch_policy_id}', 'PolicyController@updateBatchPolicy');
            $api->delete('policy/{policy}/batch_policy/{batch_policy_id}', 'PolicyController@destroyBatchPolicy');

            $api->get('coupon/batches/{couponBatch}', 'CouponBatchController@show');
            $api->get('coupon/batches', 'CouponBatchController@index');
            $api->post('company/{company}/coupon/batch', 'CouponBatchController@store');
            $api->patch('coupon/batches/{couponBatch}', 'CouponBatchController@update');

            //同步猫酷优惠券规则
            $api->get('coupon/sync', 'CouponBatchController@syncMallCooCouponBatch');

        });


    });

});