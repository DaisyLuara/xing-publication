<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\ResourceAuth\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' =>  ["api.auth", "ConvertEmptyStringsToNull"], 'model' => 'App\Models\User'], function ($api) {

            //节目授权
            $api->get('project_auth', ['middleware' => ['permission:resource_auth.project_auth.read'], 'uses' => 'ProjectAuthController@index']);
            $api->get('project_auth/{project_auth}', ['middleware' => ['permission:resource_auth.project_auth.read'], 'uses' => 'ProjectAuthController@show']);
            $api->post('project_auth', ['middleware' => ['permission:resource_auth.project_auth.create'], 'uses' => 'ProjectAuthController@store']);
            $api->patch('project_auth/{project_auth}', ['middleware' => ['permission:resource_auth.project_auth.update'], 'uses' => 'ProjectAuthController@update']);
            $api->delete('project_auth/{project_auth}', ['middleware' => ['permission:resource_auth.project_auth.delete'], 'uses' => 'ProjectAuthController@destroy']);

        });
    });

});