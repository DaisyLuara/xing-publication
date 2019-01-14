<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Payment\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            $api->get('payment/{payment}', 'PaymentController@show');
            $api->get('payment', ['middleware' => ['permission:payment.list.read'], 'uses' => 'PaymentController@index']);
            $api->post('payment', ['middleware' => ['permission:payment.list.create'], 'uses' => 'PaymentController@store']);
            $api->patch('payment/{payment}', ['middleware' => ['permission:payment.list.update'], 'uses' => 'PaymentController@update']);
            $api->delete('payment/{payment}', ['middleware' => ['permission:payment.list.delete'], 'uses' => 'PaymentController@destroy']);

            $api->post('payment/reject/{payment}', 'PaymentController@reject');
            $api->post('payment/auditing/{payment}', 'PaymentController@auditing');
            $api->post('payment/receive/{payment}', 'PaymentController@receive');

            $api->get('payment_payee/{payment_payee}', ['middleware' => ['permission:payment.payee.read'], 'uses' => 'PaymentPayeeController@show']);
            $api->get('payment_payee', ['middleware' => ['permission:payment.payee.read'], 'uses' => 'PaymentPayeeController@index']);
            $api->post('payment_payee', ['middleware' => ['permission:payment.payee.create'], 'uses' => 'PaymentPayeeController@store']);
            $api->patch('payment_payee/{payment_payee}', ['middleware' => ['permission:payment.payee.update'], 'uses' => 'PaymentPayeeController@update']);

            $api->get('payment_history', ['middleware' => ['permission:payment.history.read'], 'uses' => 'PaymentHistoryController@index']);
        });
    });

});