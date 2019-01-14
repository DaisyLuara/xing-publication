<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Media\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            $api->post('media', 'MediaController@store');
            $api->post('media_upload', 'MediaController@create');


            $api->get('media_infos', ['middleware' => ['permission:team.operation.read'], 'uses' => 'MediaInfoController@index']);
            $api->get('media_infos/{media_info}', 'MediaInfoController@show');
            $api->post('media_infos', ['middleware' => ['permission:team.operation.create'], 'uses' => 'MediaInfoController@store']);
            $api->patch('media_infos/{media_info}', ['middleware' => ['permission:team.operation.update'], 'uses' => 'MediaInfoController@update']);
            $api->delete('media_infos', ['middleware' => ['permission:team.operation.delete'], 'uses' => 'MediaInfoController@destroy']);

        });
    });

});