<?php

use App\Models\User;

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Demand\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], static function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], static function ($api) {
        $api->group(['middleware' => ['api.auth', 'ConvertEmptyStringsToNull'], 'model' => User::class], function ($api) {

            //需求申请
            $api->get('demand_applications', ['middleware' => ['permission:demand.application.read'], 'uses' => 'DemandApplicationController@index'])->name('demand_application_index');
            $api->get('demand_applications/export', ['middleware' => ['permission:demand.application.export'], 'uses' => 'DemandApplicationController@export'])->name('demand_application_export');
            $api->get('demand_applications/{demand_application}', ['middleware' => ['permission:demand.application.read'], 'uses' => 'DemandApplicationController@show'])->name('demand_application_show');
            $api->post('demand_applications', ['middleware' => ['permission:demand.application.create'], 'uses' => 'DemandApplicationController@store'])->name('demand_application_store');
            $api->patch('demand_applications/{demand_application}', ['middleware' => ['permission:demand.application.update'], 'uses' => 'DemandApplicationController@update'])->name('demand_application_update');
            $api->put('demand_applications/{demand_application}/receive', ['middleware' => ['permission:demand.application.receive|demand.application.receive_special'], 'uses' => 'DemandApplicationController@receiveDemand'])->name('demand_application_receiveDemand');
            $api->put('demand_applications/{demand_application}/confirm', ['middleware' => ['permission:demand.application.confirm'], 'uses' => 'DemandApplicationController@confirmDemand'])->name('demand_application_confirmDemand');
            $api->put('demand_applications/{demand_application}/updateContract', ['middleware' => ['permission:demand.application.create'], 'uses' => 'DemandApplicationController@updateContract'])->name('demand_application_updateContract');


            //需求修改
            $api->get('demand_modifies', ['middleware' => ['permission:demand.modify.read'], 'uses' => 'DemandModifyController@index'])->name('demand_modify_index');
            $api->get('demand_modifies/export', ['middleware' => ['permission:demand.modify.export'], 'uses' => 'DemandModifyController@export'])->name('demand_modify_export');
            $api->get('demand_modifies/{demand_modify}', ['middleware' => ['permission:demand.modify.read'], 'uses' => 'DemandModifyController@show'])->name('demand_modify_show');
            $api->post('demand_modifies', ['middleware' => ['permission:demand.modify.create'], 'uses' => 'DemandModifyController@store'])->name('demand_modify_store');
            $api->patch('demand_modifies/{demand_modify}', ['middleware' => ['permission:demand.modify.update'], 'uses' => 'DemandModifyController@update'])->name('demand_modify_update');
            $api->put('demand_modifies/{demand_modify}/review', ['middleware' => ['permission:demand.modify.review'], 'uses' => 'DemandModifyController@reviewDemandModify'])->name('demand_modify_reviewDemandModify');
            $api->put('demand_modifies/{demand_modify}/feedback', ['middleware' => ['permission:demand.modify.feedback'], 'uses' => 'DemandModifyController@feedbackDemandModify'])->name('demand_modify_feedbackDemandModify');

        });
    });

});