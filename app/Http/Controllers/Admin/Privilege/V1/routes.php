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

            $api->get('permission/{permission}', 'PermissionController@show');
            $api->get('permission', 'PermissionController@index');
            $api->post('permission', 'PermissionController@store');
            $api->patch('permission/{permission}', 'PermissionController@update');
            $api->delete('permission/{permission}', 'PermissionController@destroy');

            $api->get('role/{role}', 'RoleController@show');
            $api->get('role', 'RoleController@index');
            $api->post('role', 'RoleController@store');
            $api->patch('role/{role}', 'RoleController@update');
            $api->delete('role/{role}', 'RoleController@destroy');
        });
    });

});