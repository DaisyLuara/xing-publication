<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Point\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->get('point_configs/{point_config}', 'PointConfigController@show');//点位配置

        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            //热力地图
            $api->get('point/map', 'PointController@map');

            //场地
            $api->get('markets', 'MarketController@index');
            $api->get('markets/{market}', 'MarketController@show');
            $api->post('market', ['middleware' => ['role:super-admin|admin|project-manager'], 'uses' => 'MarketController@store']);
            $api->patch('market/{market}', ['middleware' => ['role:super-admin|admin|project-manager'], 'uses' => 'MarketController@update']);

            //点位
            $api->get('points', 'PointController@index');
            $api->get('points/{point}', 'PointController@show');
            $api->post('point', ['middleware' => ['role:super-admin|admin|project-manager'], 'uses' => 'PointController@store']);
            $api->patch('points/{point}', ['middleware' => ['role:super-admin|admin|project-manager'], 'uses' => 'PointController@update']);

            //门店
            $api->get('stores', 'StoreController@index');
            $api->get('stores/{store}', 'StoreController@show');
            $api->post('stores', ['middleware' => ['role:super-admin|admin|user|bd-manager|legal-affairs|legal-affairs-manager'], 'uses' => 'StoreController@store']);
            $api->patch('stores/{store}', ['middleware' => ['role:super-admin|admin|user|bd-manager|legal-affairs|legal-affairs-manager'], 'uses' => 'StoreController@update']);
        });
    });

});