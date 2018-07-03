<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Ad\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            //广告投放
            $api->get('ad_launch', 'AdLaunchController@index');
            $api->post('ad_launch', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'AdLaunchController@store']);
            $api->patch('ad_launch', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'AdLaunchController@update']);

            //广告
            $api->get('advertisement', 'AdvertisementController@index');
            $api->post('advertisement', 'AdvertisementController@store');
            $api->patch('advertisement', 'AdvertisementController@update');

            //广告主
            $api->get('advertiser', 'AdvertiserController@index');
            $api->post('advertiser', 'AdvertiserController@store');
            $api->patch('advertiser', 'AdvertiserController@update');

            //广告行业
            $api->get('ad_trade', 'AdTradeController@index');
            $api->post('ad_trade', 'AdTradeController@store');
            $api->patch('ad_trade', 'AdTradeController@update');
        });
    });

});