<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Warehouse\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            //产品
            $api->get('erp_product/export', ['middleware' => ['permission:storage.product.read'], 'uses' => 'ProductController@export']);
            $api->get('erp_product/{product}', 'ProductController@show');
            $api->get('erp_product', ['middleware' => ['permission:storage.product.read'], 'uses' => 'ProductController@index']);
            $api->post('erp_product', ['middleware' => ['permission:storage.product.create'], 'uses' => 'ProductController@store']);
            $api->patch('erp_product/{product}', ['middleware' => ['permission:storage.product.update'], 'uses' => 'ProductController@update']);

            //根据产品SKU，查出对应产品属性
            $api->get('product_attribute', 'ProductAttributeController@list');

            //仓库
            $api->get('erp_warehouse/export', ['middleware' => ['permission:storage.store.read'], 'uses' => 'WarehouseController@export']);
            $api->get('erp_warehouse/{warehouse}', 'WarehouseController@show');
            $api->get('erp_warehouse', ['middleware' => ['permission:storage.store.read'], 'uses' => 'WarehouseController@index']);
            $api->post('erp_warehouse', ['middleware' => ['permission:storage.store.create'], 'uses' => 'WarehouseController@store']);
            $api->patch('erp_warehouse/{warehouse}', ['middleware' => ['permission:storage.store.update'], 'uses' => 'WarehouseController@update']);

            //库位
            $api->get('erp_location/export', ['middleware' => ['permission:storage.location.read'], 'uses' => 'LocationController@export']);
            $api->get('erp_location/{location}', 'LocationController@show');
            $api->get('erp_location', ['middleware' => ['permission:storage.location.read'], 'uses' => 'LocationController@index']);
            $api->post('erp_location', ['middleware' => ['permission:storage.location.create'], 'uses' => 'LocationController@store']);
            $api->patch('erp_location/{location}', ['middleware' => ['permission:storage.location.update'], 'uses' => 'LocationController@update']);

            //调拨记录
            $api->get('erp_warehouse_change/export', ['middleware' => ['permission:storage.records.read'], 'uses' => 'WarehouseChangeController@export']);
            $api->get('erp_warehouse_change/{warehouseChange}', 'WarehouseChangeController@show');
            $api->get('erp_warehouse_change', ['middleware' => ['permission:storage.records.read'], 'uses' => 'WarehouseChangeController@index']);
            $api->post('erp_warehouse_change', ['middleware' => ['permission:storage.records.create'], 'uses' => 'WarehouseChangeController@store']);


            //合同硬件出厂
            $api->get('contract_product', 'ProductFactoryController@index');
            $api->post('erp_warehouse_change/factory', ['middleware' => ['permission:contract.list.factory'], 'uses' => 'WarehouseChangeController@factory']);

            //库存明细
            $api->get('location_product/export', ['middleware' => ['permission:storage.list.read'], 'uses' => 'LocationProductController@export']);
            $api->get('location_product', ['middleware' => ['permission:storage.list.read'], 'uses' => 'LocationProductController@index']);


            //attribute
            $api->get('erp_attribute/{attribute}', 'AttributeController@show');
            $api->get('erp_attribute', 'AttributeController@index');
            $api->post('erp_attribute', 'AttributeController@store');
            $api->patch('erp_attribute/{attribute}', 'AttributeController@update');
            $api->delete('erp_attribute/{attribute}', 'AttributeController@delete');

            //attribute value
            $api->get('erp_attribute/{attribute}/value', 'AttributeValueController@index');
            $api->post('erp_attribute/{attribute}/value', 'AttributeValueController@store');
            $api->delete('erp_attribute/{attribute}/value/{value}', 'AttributeValueController@delete');

        });
    });

});