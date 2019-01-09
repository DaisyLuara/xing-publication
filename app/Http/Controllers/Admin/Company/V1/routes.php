<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Company\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            //公司管理
            $api->get('companies', 'AdminCompaniesController@index');
            $api->get('companies/{company}', 'AdminCompaniesController@show');
            $api->post('companies', ['middleware' => ['role:user|bd-manager|legal-affairs|legal-affairs-manager'], 'uses' => 'AdminCompaniesController@store']);
            $api->patch('companies/{company}', ['middleware' => ['role:user|bd-manager|legal-affairs|legal-affairs-manager'], 'uses' => 'AdminCompaniesController@update']);

            //公司客户管理
            $api->get('companies/{company}/customers', 'AdminCustomersController@index');
            $api->get('companies/{company}/customers/{customer}', 'AdminCustomersController@show');
            $api->post('companies/{company}/customers', ['middleware' => ['permission:company'], 'uses' => 'AdminCustomersController@store']);
            $api->patch('companies/{company}/customers/{customer}', ['middleware' => ['permission:company'], 'uses' => 'AdminCustomersController@update']);

            //商户权限
            $api->get('company_permission/{permission}', 'CompanyPermissionController@show');
            $api->get('company_permission', 'CompanyPermissionController@index');
            $api->post('company_permission', 'CompanyPermissionController@store');
            $api->patch('company_permission/{permission}', 'CompanyPermissionController@update');
            $api->delete('company_permission/{permission}', 'CompanyPermissionController@destroy');

            //商户角色
            $api->get('company_role/{role}', 'CompanyRoleController@show');
            $api->get('company_role', 'CompanyRoleController@index');
            $api->post('company_role', 'CompanyRoleController@store');
            $api->patch('company_role/{role}', 'CompanyRoleController@update');
            $api->delete('company_role/{role}', 'CompanyRoleController@destroy');
        });
    });

});