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
            //产品列表
            $api->get('product/list', ['middleware' => ['permission::storage.product.read'], 'uses' => 'ProductController@index']);
            //产品详情
            $api->get('product/{product}', 'ProductController@show');
            //新增产品
            $api->post('product/create', ['middleware' => ['permission::storage.product.create'], 'uses' => 'ProductController@store']);
            //编辑产品
            $api->patch('product/{product}', ['middleware' => ['permission::storage.product.update'], 'uses' => 'ProductController@update']);

            //产品属性列表
            $api->get('attribute/list', 'AttributeController@list');
            //根据产品SKU，查出对应产品属性
            $api->get('product_attribute', 'ProductAttributeController@list');

            //仓库列表
            $api->get('warehouse/list', ['middleware' => ['permission::storage.store.read'], 'uses' => 'WarehouseController@index']);
            //仓库详情
            $api->get('warehouse/{warehouse}', 'WarehouseController@show');
            //新增仓库
            $api->post('warehouse/create', ['middleware' => ['permission::storage.store.create'], 'uses' => 'WarehouseController@store']);
            //编辑仓库
            $api->patch('warehouse/{warehouse}', ['middleware' => ['permission::storage.store.update'], 'uses' => 'WarehouseController@update']);

            //库位列表
            $api->get('location/list', ['middleware' => ['permission::storage.location.read'], 'uses' => 'LocationController@index']);
            //库位详情
            $api->get('location/{location}', 'LocationController@show');
            //新增库位
            $api->post('location/create', ['middleware' => ['permission::storage.location.create'], 'uses' => 'LocationController@store']);
            //编辑库位
            $api->patch('location/{location}', ['middleware' => ['permission::storage.location.update'], 'uses' => 'LocationController@update']);

            //新增调拨记录
            $api->post('warehousechange/create', ['middleware' => ['permission::storage.records.create'], 'uses' => 'WarehouseChangeController@create']);
            //调拨记录列表
            $api->get('warehousechange/list', ['middlerware' => ['permission::storage.records.read'], 'uses' => 'WarehouseChangeController@list']);
            //单个调拨记录详情
            $api->get('warehousechange/{warehousechange}', 'WarehouseChangeController@show');

            //产品库存明细，列出产品名称、产品颜色、仓库、库位、库存数量
            $api->get('location_product', ['middleware' => ['permission::storage.list.read'], 'uses' => 'LocationProductController@list']);

            //硬件出厂,批量增加调拨记录
            $api->post('warehousechange/chuchang', ['middleware' => ['permission::contract.list.leaveFactory_create'], 'uses' => 'WarehouseChangeController@chuchang']);
            //合同页面出厂详情
            $api->get('contract_product', 'ProductChuchangController@index');
        });
    });

});