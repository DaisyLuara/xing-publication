<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\ShortUrl\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->group(['middleware' => 'api.auth', 'model' => 'App\Models\User'], function ($api) {
            $api->get('short_urls', ['middleware' => ['permission:ad.url.read'], 'uses' => 'ShortUrlController@index']);
            $api->post('short_urls', ['middleware' => ['permission:ad.url.create'], 'uses' => 'ShortUrlController@store']);
            $api->get('short_urls/export', ['middleware' => ['permission:ad.url.export'], 'uses' => 'ShortUrlController@export']);
        });
    });

});