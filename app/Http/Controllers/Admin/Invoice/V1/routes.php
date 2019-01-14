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

            $api->get('invoice/{invoice}', 'InvoiceController@show');
            $api->get('invoice', ['middleware' => ['permission:invoice.list.read'], 'uses' => 'InvoiceController@index']);
            $api->post('invoice', ['middleware' => ['permission:invoice.list.create'], 'uses' => 'InvoiceController@store']);
            $api->patch('invoice/{invoice}', ['middleware' => ['permission:invoice.list.update'], 'uses' => 'InvoiceController@update']);
            $api->delete('invoice/{invoice}', ['middleware' => ['permission:invoice.list.delete'], 'uses' => 'InvoiceController@destroy']);

            $api->post('invoice/reject/{invoice}', 'InvoiceController@reject');
            $api->post('invoice/auditing/{invoice}', 'InvoiceController@auditing');
            $api->post('invoice/receive/{invoice}', 'InvoiceController@receive');

            //开票公司
            $api->get('invoice_company/{invoice_company}', 'InvoiceCompanyController@show');
            $api->get('invoice_company', ['middleware' => ['permission:invoice.company.read'], 'uses' => 'InvoiceCompanyController@index']);
            $api->post('invoice_company', ['middleware' => ['permission:invoice.company.create'], 'uses' => 'InvoiceCompanyController@store']);
            $api->patch('invoice_company/{invoice_company}', ['middleware' => ['permission:invoice.company.update'], 'uses' => 'InvoiceCompanyController@update']);

            $api->get('invoice_history', ['middleware' => ['permission:invoice.history.read'], 'uses' => 'InvoiceHistoryController@index']);

            //财务收款
            $api->get('invoice_receipt/{invoice_receipt}', 'InvoiceReceiptController@show');
            $api->get('invoice_receipt', ['middleware' => ['permission:invoice.receipt.read'], 'uses' => 'InvoiceReceiptController@index']);
            $api->post('invoice_receipt', ['middleware' => ['permission:invoice.receipt.create'], 'uses' => 'InvoiceReceiptController@store']);
            $api->patch('invoice_receipt/{invoice_receipt}', ['middleware' => ['permission:invoice.receipt.update'], 'uses' => 'InvoiceReceiptController@update']);

            $api->post('invoice_receipt/confirm/{invoice_receipt}', ['middleware' => ['permission:invoice.receipt.confirm'], 'uses' => 'InvoiceReceiptController@confirm']);
        });
    });

});