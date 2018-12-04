<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Invoice\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->group(['prefix' => 'mallcoo', 'namespace' => 'App\Http\Controllers\Admin\MallCoo\V1\Api'], function ($api) {

            $api->any('user/oauth', 'UserAPIController@oauth');
            $api->any('user/callback', 'UserAPIController@callback');

            $api->get('promotion/categories', 'PromotionAPIController@getCategoryList');
            $api->get('promotion', 'PromotionAPIController@getList');

            $api->get('coupon', 'CouponAPIController@index');
            $api->post('coupon', 'CouponAPIController@store');
        });
    });

});