<?php

use App\Models\User;

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Ad\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], static function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], static function ($api) {
        $api->group(['middleware' => 'api.auth', 'model' => User::class], static function ($api) {

            //广告投放
            $api->get('ad_launch', ['middleware' => ['permission:ad.item.read'], 'uses' => 'AdLaunchController@index']);
            $api->post('ad_launch', ['middleware' => ['permission:ad.item.create'], 'uses' => 'AdLaunchController@store']);
            $api->patch('ad_launch', ['middleware' => ['permission:ad.item.update'], 'uses' => 'AdLaunchController@update']);

            //广告
            $api->get('advertisement', 'AdvertisementController@index');
            $api->post('advertisement', 'AdvertisementController@store');
            $api->patch('advertisement/{advertisement}', 'AdvertisementController@update');

            //广告方案
            $api->get('ad_plan', 'AdPlanController@index');
            $api->get('ad_plan/{ad_plan}', 'AdPlanController@show');
            $api->post('ad_plan', 'AdPlanController@store');
            $api->patch('ad_plan/{ad_plan}', 'AdPlanController@updateBatch');
            $api->put('ad_plan/{ad_plan}', 'AdPlanController@update');

            //编辑单条广告方案排期
            $api->post('ad_plan_time/{ad_plan}/ad_plan', 'AdPlanTimeController@store');
            $api->patch('ad_plan_time/{ad_plan_time}', 'AdPlanTimeController@update');

            //广告行业
            $api->get('ad_trade', 'AdTradeController@index');
            $api->post('ad_trade', 'AdTradeController@store');
            $api->patch('ad_trade', 'AdTradeController@update');
        });
    });

});