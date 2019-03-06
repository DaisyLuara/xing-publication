<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Demand\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => ["api.auth", "ConvertEmptyStringsToNull"], 'model' => 'App\Models\User'], function ($api) {

            //需求申请
            $api->get('demand_applications', 'DemandApplicationController@index');
            $api->get('demand_applications/{demand_application}', ['middleware' => ['permission:demand.application.read'], 'uses' => 'DemandApplicationController@show'])->name('demand_application_show');
            $api->post('demand_applications', ['middleware' => ['permission:demand.application.create'], 'uses' => 'DemandApplicationController@store'])->name('demand_application_store');
            $api->patch('demand_applications/{demand_application}', ['middleware' => ['permission:demand.application.update'], 'uses' => 'DemandApplicationController@update'])->name('demand_application_update');
            $api->put('demand_applications/{demand_application}/receive', ['middleware' => ['permission:demand.application.receive,demand.application.receive_special'], 'uses' => 'DemandApplicationController@receiveDemand'])->name('demand_application_receiveDemand');
            $api->put('demand_applications/{demand_application}/confirm', ['middleware' => ['permission:demand.application.confirm'], 'uses' => 'DemandApplicationController@confirmDemand'])->name('demand_application_confirmDemand');

            //需求修改

        });
    });

});