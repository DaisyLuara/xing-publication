<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Company\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], static function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], static function ($api) {
        $api->group(['middleware' => 'api.auth', 'model' => 'App\Models\User'], static function ($api) {

            //公司管理
            $api->get('companies/export', ['middleware' => ['permission:company.customers.export'], 'uses' => 'AdminCompaniesController@export']);
            $api->get('companies', ['middleware' => ['permission:company.customers.read'], 'uses' => 'AdminCompaniesController@index']);
            $api->get('companies/{company}', 'AdminCompaniesController@show');
            $api->post('companies', ['middleware' => ['permission:company.customers.create'], 'uses' => 'AdminCompaniesController@store']);
            $api->patch('companies/{company}', ['middleware' => ['permission:company.customers.update'], 'uses' => 'AdminCompaniesController@update']);

            //公司客户管理
            $api->get('companies/{company}/customers', ['middleware' => ['permission:company.customers.contacts_read'], 'uses' => 'AdminCustomersController@index']);
            $api->get('companies/{company}/customers/{customer}', 'AdminCustomersController@show');
            $api->post('companies/{company}/customers', ['middleware' => ['permission:company.customers.contacts_create'], 'uses' => 'AdminCustomersController@store']);
            $api->patch('companies/{company}/customers/{customer}', ['middleware' => ['permission:company.customers.contacts_update'], 'uses' => 'AdminCustomersController@update']);

            //商户权限
            $api->get('company_permission/{permission}', 'CompanyPermissionController@show');
            $api->get('company_permission', ['middleware' => ['permission:company.permission.read'], 'uses' => 'CompanyPermissionController@index']);
            $api->post('company_permission', ['middleware' => ['permission:company.permission.create'], 'uses' => 'CompanyPermissionController@store']);
            $api->patch('company_permission/{permission}', ['middleware' => ['permission:company.permission.update'], 'uses' => 'CompanyPermissionController@update']);
            $api->delete('company_permission/{permission}', ['middleware' => ['permission:company.permission.delete'], 'uses' => 'CompanyPermissionController@destroy']);

            //商户角色
            $api->get('company_role/{role}', 'CompanyRoleController@show');
            $api->get('company_role', ['middleware' => ['permission:company.role.read'], 'uses' => 'CompanyRoleController@index']);
            $api->post('company_role', ['middleware' => ['permission:company.role.create'], 'uses' => 'CompanyRoleController@store']);
            $api->patch('company_role/{role}', ['middleware' => ['permission:company.role.update'], 'uses' => 'CompanyRoleController@update']);
            $api->delete('company_role/{role}', ['middleware' => ['permission:company.role.delete'], 'uses' => 'CompanyRoleController@destroy']);

            //公司资源审核
            $api->get('company_media', ['middleware' => [], 'uses' => 'CompanyMediaController@index']);
            $api->patch('company_media/audit/{media}',['middleware'=>[],'uses'=>'CompanyMediaController@audit']);
        });
    });

});