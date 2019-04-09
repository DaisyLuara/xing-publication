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
            $api->get('contract/export', ['middleware' => ['permission:team.program.download'], 'uses' => 'ContractController@export']); //下载
            $api->get('contract/{contract}', 'ContractController@show');
            $api->get('contract', ['middleware' => ['permission:contract.list.read'], 'uses' => 'ContractController@index']);
            $api->post('contract', ['middleware' => ['permission:contract.list.create'], 'uses' => 'ContractController@store']);
            $api->patch('contract/{contract}', ['middleware' => ['permission:contract.list.update'], 'uses' => 'ContractController@update']);
            $api->delete('contract/{contract}', ['middleware' => ['permission:contract.list.delete'], 'uses' => 'ContractController@destroy']);

            $api->post('contract/reject/{contract}', ['middleware' => ['permission:contract.list.reject'], 'uses' => 'ContractController@reject']);
            $api->post('contract/auditing/{contract}', ['middleware' => ['permission:contract.list.auditing'], 'uses' => 'ContractController@auditing']);
            $api->post('contract/special_auditing/{contract}', ['middleware' => ['permission:contract.list.special_auditing'], 'uses' => 'ContractController@specialAuditing']);

            //收款提示
            $api->get('remind_contract', ['middleware' => ['permission:contract.collection.read'], 'uses' => 'ContractReceiveDateController@index']);

            // 审批历史
            $api->get('contract_history', ['middleware' => ['permission:contract.history.read'], 'uses' => 'ContractHistoryController@index']);

            //待审批数
            $api->get('auditing_count', 'ContractController@count');

            //成本管理
            $api->get('contract_cost/{contract_cost}', 'ContractCostController@show');
            $api->get('contract_cost', ['middleware' => ['permission:contract.cost.read'], 'uses' => 'ContractCostController@index']);
            $api->post('contract_cost', ['middleware' => ['permission:contract.cost.create'], 'uses' => 'ContractCostController@store']);

            //成本明细
            $api->post('contract_cost/{contract_cost}/cost_content', ['middleware' => ['permission:contract.cost.content_create'], 'uses' => 'ContractCostContentController@store']);
            $api->patch('contract_cost/{contract_cost}/cost_content/{content}', ['middleware' => ['permission:contract.cost.content_update'], 'uses' => 'ContractCostContentController@update']);
            $api->delete('contract_cost/{contract_cost}/cost_content/{content}', ['middleware' => ['permission:contract.cost.content_delete'], 'uses' => 'ContractCostContentController@destroy']);

            //成本确认
            $api->post('cost_content/{content}/confirm', ['middleware' => ['permission:contract.cost.content_confirm'], 'uses' => 'ContractCostContentController@confirm']);
        });
    });

});