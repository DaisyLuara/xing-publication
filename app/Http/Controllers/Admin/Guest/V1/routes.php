<?php

use App\Models\User;

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Guest\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], static function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], static function ($api) {
        $api->group(['middleware' => 'api.auth', 'model' => User::class], static function ($api) {
            $api->get('guest_mobiles', 'GuestMobileController@index');
        });
    });

});