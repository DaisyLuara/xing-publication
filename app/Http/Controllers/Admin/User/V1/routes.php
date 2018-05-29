<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\User\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            //当前登录用户信息
            $api->get('user', 'UsersController@me');
            $api->patch('user', 'UsersController@update');//patch 部分修改资源，提供部分资源信息


            // 权限设置
            $api->get('system/users', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@index']);
            $api->get('system/users/{user}', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@show']);
            $api->post('system/users', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@store']);
            $api->patch('system/users/{user}', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@update']);
            $api->get('system/roles', ['middleware' => ['role:super-admin|admin'], 'uses' => 'RolesController@index']);

        });
    });

});