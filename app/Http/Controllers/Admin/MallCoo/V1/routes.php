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

            $api->post('user/oauth', 'UserController@oauth');//获取授权页面url
            $api->any('user/callback', 'UserController@callback');//授权回调
            $api->any('user/byToken', 'UserController@getUserByToken');
            $api->any('user/byOpenUserId', 'UserController@getUserByOpenUserID');
            $api->post('users', 'UserController@store'); //手机号开通会员卡
            $api->get('user', 'UserController@show');//获取商场会员信息

            $api->get('coupon', 'CouponController@index');
            $api->post('coupon', 'CouponController@store');
        });
    });

});