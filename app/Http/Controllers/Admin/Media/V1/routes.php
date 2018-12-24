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


            $api->get('media_infos', ['middleware' => ['role:bonus-manager|legal-affairs-manager|operation'], 'uses' => 'MediaInfoController@index']);
            $api->post('media_infos', ['middleware' => ['role:bonus-manager|legal-affairs-manager|operation'], 'uses' => 'MediaInfoController@store']);
            $api->patch('media_infos/{media_info}', ['middleware' => ['role:bonus-manager|legal-affairs-manager|operation'], 'uses' => 'MediaInfoController@update']);
            $api->delete('media_infos', ['middleware' => ['role:bonus-manager|legal-affairs-manager|operation'], 'uses' => 'MediaInfoController@destroy']);

        });
    });

});