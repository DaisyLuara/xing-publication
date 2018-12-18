<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Team\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            $api->get('team_project/{team_project}', 'TeamProjectController@show');
            $api->get('team_project', 'TeamProjectController@index');
            $api->post('team_project', 'TeamProjectController@store');
            $api->patch('team_project/{team_project}', 'TeamProjectController@update');

            $api->post('team_project/confirm/{team_project}', 'TeamProjectController@confirm');

            //比例分配
            $api->get('team_rate/{team_rate}', 'TeamRateController@show');
            $api->get('team_rate', 'TeamRateController@index');
            $api->patch('team_rate/{team_rate}', 'TeamRateController@update');

            //平台项目
            $api->get('team_system_project/{team_system_project}', 'TeamSystemProjectController@show');
            $api->get('team_system_project', 'TeamSystemProjectController@index');
            $api->post('team_system_project', 'TeamSystemProjectController@store');
            //申请驳回
            $api->post('team_system_project/reject/{team_system_project}', 'TeamSystemProjectController@reject');
            //奖金分配
            $api->post('team_system_project/distribute/{team_system_project}', 'TeamSystemProjectController@distribute');
            //平台总奖金
            $api->get('system_bonus', 'TeamSystemProjectController@systemBonus');
            //已分配的奖金
            $api->get('distribution_bonus', 'TeamSystemProjectController@distributionBonus');
            //平台明细
            $api->get('system_detail/{team_person_reward}', 'TeamSystemProjectController@detailShow');
            $api->get('system_detail', 'TeamSystemProjectController@detailIndex');
            $api->post('system_detail', 'TeamSystemProjectController@detailStore');
            $api->patch('system_detail/{team_person_reward}', 'TeamSystemProjectController@detailUpdate');


            //个人中心奖金
            $api->get('person_reward', 'TeamPersonRewardController@index');
            $api->get('person_reward/total', 'TeamPersonRewardController@totalReward');
        });
    });

});