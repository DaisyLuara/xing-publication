<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Contract\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            //合同管理
            $api->get('contract/{contract}', 'ContractController@show');
            $api->get('contract', ['middleware' => ['permission:contract.list.read'], 'uses' => 'ContractController@index']);
            $api->post('contract', ['middleware' => ['permission:contract.list.create'], 'uses' => 'ContractController@store']);
            $api->patch('contract/{contract}', ['middleware' => ['permission:contract.list.update'], 'uses' => 'ContractController@update']);
            $api->delete('contract/{contract}', ['middleware' => ['permission:contract.list.delete'], 'uses' => 'ContractController@destroy']);

            $api->post('contract/reject/{contract}', 'ContractController@reject');
            $api->post('contract/auditing/{contract}', 'ContractController@auditing');
            $api->post('contract/special_auditing/{contract}', 'ContractController@specialAuditing');

            //收款提示
            $api->get('remind_contract', ['middleware' => ['permission:contract.collection.read'], 'uses' => 'ContractReceiveDateController@index']);

            // 审批历史
            $api->get('contract_history', ['middleware' => ['permission:contract.history.read'], 'uses' => 'ContractHistoryController@index']);

            //待审批数
            $api->get('auditing_count', 'ContractController@count');

        });
    });

});