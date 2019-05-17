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
            $api->get('advertisement', ['middleware' => ['permission:ad.advertisement.read'], 'uses' =>'AdvertisementController@index']);
            $api->post('advertisement', ['middleware' => ['permission:ad.advertisement.create'], 'uses' =>'AdvertisementController@store']);
            $api->patch('advertisement/{advertisement}', ['middleware' => ['permission:ad.advertisement.update'], 'uses' =>'AdvertisementController@update']);

            //广告方案
            $api->get('ad_plan', ['middleware' => ['permission:ad.plan.read'], 'uses' =>'AdPlanController@index']);
            $api->get('ad_plan/{ad_plan}', ['middleware' => ['permission:ad.plan.read'], 'uses' =>'AdPlanController@show'])->where('ad_plan','[0-9]+');
            $api->post('ad_plan', ['middleware' => ['permission:ad.plan.create'], 'uses' =>'AdPlanController@store']);
            $api->patch('ad_plan/{ad_plan}', ['middleware' => ['permission:ad.plan.update'], 'uses' =>'AdPlanController@updateBatch'])->where('ad_plan','[0-9]+');
            $api->put('ad_plan/{ad_plan}', ['middleware' => ['permission:ad.plan.update'], 'uses' =>'AdPlanController@update'])->where('ad_plan','[0-9]+');

            //编辑单条广告方案排期
            $api->get('ad_plan_time/{ad_plan_time}', ['middleware' => ['permission:ad.plan.read'], 'uses' =>'AdPlanTimeController@show'])->where('ad_plan_time','[0-9]+');
            $api->post('ad_plan_time/{ad_plan}/ad_plan', ['middleware' => ['permission:ad.plan.create'], 'uses' =>'AdPlanTimeController@store'])->where('ad_plan','[0-9]+');
            $api->patch('ad_plan_time/{ad_plan_time}', ['middleware' => ['permission:ad.plan.update'], 'uses' =>'AdPlanTimeController@update'])->where('ad_plan_time','[0-9]+');

            //广告行业
            $api->get('ad_trade', ['middleware' => ['permission:ad.trade.read'], 'uses' =>'AdTradeController@index']);
            $api->post('ad_trade', ['middleware' => ['permission:ad.trade.create'], 'uses' =>'AdTradeController@store']);
            $api->patch('ad_trade', ['middleware' => ['permission:ad.trade.update'], 'uses' =>'AdTradeController@update']);
        });
    });

});