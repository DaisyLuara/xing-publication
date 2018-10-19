<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Invoice\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            $api->get('invoice/{invoice}','InvoiceController@show');
            $api->get('invoice','InvoiceController@index');
            $api->post('invoice','InvoiceController@store');
            $api->patch('invoice','InvoiceController@update');
            $api->delete('invoice/{invoice}','InvoiceController@destroy');

            $api->post('invoice/auditing/{invoice}', 'InvoiceController@auditing');
            $api->post('invoice/receive/{invoice}','InvoiceController@receive');
        });
    });

});