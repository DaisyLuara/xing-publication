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

            $api->any('user/oauth', 'UserController@oauth');
            $api->any('user/callback', 'UserController@callback');
            $api->any('user/byToken', 'UserController@getUserByToken');
            $api->any('user/byOpenUserId', 'UserController@getUserByOpenUserID');

            $api->get('promotion/categories', 'PromotionController@getCategoryList');
            $api->get('promotion', 'PromotionAPIController@getList');

            $api->get('coupon', 'CouponController@index');
            $api->post('coupon', 'CouponController@store');
        });
    });

});