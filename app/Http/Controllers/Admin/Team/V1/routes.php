<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Team\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            $api->get('team_project/{team_project}', 'TeamProjectController@show');
            $api->get('team_project', 'TeamProjectController@index');
            $api->post('team_project', 'TeamProjectController@store');
            $api->patch('team_project/{team_project}', 'TeamProjectController@update');

            $api->post('team_project/confirm/{team_project}', 'TeamProjectController@confirm');

            //比例分配
            $api->get('team_rate/{team_rate}', 'TeamRateController@show');
            $api->get('team_rate', 'TeamRateController@index');
            $api->post('team_rate', 'TeamRateController@store');
            $api->patch('team_rate/{team_rate}', 'TeamRateController@update');

            //平台项目
            $api->get('team_system_project/{team_system_project}', 'TeamSystemProjectController@show');
            $api->get('team_system_project', 'TeamSystemProjectController@index');
            $api->post('team_system_project', 'TeamSystemProjectController@store');
        });
    });

});