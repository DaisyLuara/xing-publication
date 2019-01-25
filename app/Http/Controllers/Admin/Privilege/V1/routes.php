<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Privilege\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            $api->get('permission/{permission}', ['middleware' => ['permission:system.permission.read'], 'uses' => 'PermissionController@show']);
            $api->get('permission', ['middleware' => ['permission:system.permission.read'], 'uses' => 'PermissionController@index']);
            $api->post('permission', ['middleware' => ['permission:system.permission.create'], 'uses' => 'PermissionController@store']);
            $api->patch('permission/{permission}', ['middleware' => ['permission:system.permission.update'], 'uses' => 'PermissionController@update']);
            $api->delete('permission/{permission}', ['middleware' => ['permission:system.permission.delete'], 'uses' => 'PermissionController@destroy']);

            $api->get('role/{role}', ['middleware' => ['permission:system.role.read'], 'uses' => 'RoleController@show']);
            $api->get('role', ['middleware' => ['permission:system.role.read'], 'uses' => 'RoleController@index']);
            $api->post('role', ['middleware' => ['permission:system.role.create'], 'uses' => 'RoleController@store']);
            $api->patch('role/{role}', ['middleware' => ['permission:system.role.update'], 'uses' => 'RoleController@update']);
            $api->delete('role/{role}', ['middleware' => ['permission:system.role.delete'], 'uses' => 'RoleController@destroy']);
        });
    });

});