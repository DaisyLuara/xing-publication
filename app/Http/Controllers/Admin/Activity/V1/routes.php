<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Activity\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->get('point_configs/{point_config}', 'PointConfigController@show');//点位配置

        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            //活动参与者
            $api->get('activity_participants', 'ActivityParticipantsController@index');

            //红包流水
            $api->get('red_pack_bills', 'RedPackBillController@index');

            //发送红包
            $api->post('activity_participants/redpack', 'ActivityParticipantsController@redPack');
        });
    });

});