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

            //优惠券策略
            $api->get('coupon/policies/{policy}', 'PolicyController@show');
            $api->get('coupon/policies', ['middleware' => ['permission:project.strategy.read'], 'uses' => 'PolicyController@index']);
            $api->post('company/{company}/coupon/policy', ['middleware' => ['permission:project.strategy.create'], 'uses' => 'PolicyController@store']);
            $api->patch('coupon/policy/{policy}', ['middleware' => ['permission:project.strategy.update'], 'uses' => 'PolicyController@update']);
            //子条目
            $api->get('policy/{policy}/batch_policies', ['middleware' => ['permission:project.strategy.childRead'], 'uses' => 'PolicyController@batchPolicyIndex']);
            $api->post('policy/{policy}', ['middleware' => ['permission:project.strategy.childCreate'], 'uses' => 'PolicyController@storeBatchPolicy']);
            $api->patch('policy/{policy}/batch_policy/{batch_policy_id}', ['middleware' => ['permission:project.strategy.childUpdate'], 'uses' => 'PolicyController@updateBatchPolicy']);
            $api->delete('policy/{policy}/batch_policy/{batch_policy_id}', ['middleware' => ['permission:project.strategy.childDelete'], 'uses' => 'PolicyController@destroyBatchPolicy']);

            //优惠券规则
            $api->get('coupon/batches/{couponBatch}', 'CouponBatchController@show');
            $api->get('coupon/batches', ['middleware' => ['permission:project.rules.read'], 'uses' => 'CouponBatchController@index']);
            $api->post('company/{company}/coupon/batch', ['middleware' => ['permission:project.rules.create'], 'uses' => 'CouponBatchController@store']);
            $api->patch('coupon/batches/{couponBatch}', ['middleware' => ['permission:project.rules.update'], 'uses' => 'CouponBatchController@update']);

            //优惠券投放
            $api->get('coupons', ['middleware' => ['permission:project.coupon.read'], 'uses' => 'CouponController@index']);
            $api->get('coupons/export', ['middleware' => ['permission:project.coupon.download'], 'uses' => 'CouponController@export']);
            //同步猫酷优惠券规则
            $api->get('coupon/sync', 'CouponBatchController@syncMallCooCouponBatch');

        });

    });

});