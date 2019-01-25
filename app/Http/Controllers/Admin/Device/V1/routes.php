<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Device\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            //设备
            $api->get('push', ['middleware' => ['permission:device.item.read'], 'uses' => 'PushController@index']);

            //天猫-数据回流
            $api->get('tmall/feedback', ['middleware' => ['permission:device.feedback.read'], 'uses' => 'FeedBackController@index']);
        });
    });

});