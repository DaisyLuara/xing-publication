<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Product\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            $api->get('product/{product}', 'ProductController@show');
            $api->get('products', 'ProductController@index');
            $api->post('products/{product}', 'ProductController@store');
            $api->patch('product/{product}', 'ProductController@update');
        });
    });

});