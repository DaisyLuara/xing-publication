<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Project\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            //落地方式配置
            $api->get('project_ad_launch', 'ProjectAdLaunchController@index');
            $api->post('project_ad_launch', 'ProjectAdLaunchController@store');
            $api->patch('project_ad_launch', 'ProjectAdLaunchController@update');

            //节目投放
            $api->get('projects/launch', ['middleware' => ['permission:project.item.read'], 'uses' => 'ProjectLaunchController@index']);
            $api->post('projects/launch', ['middleware' => ['permission:project.item.create'], 'uses' => 'ProjectLaunchController@store']);
            $api->patch('projects/launches', ['middleware' => ['permission:project.item.update'], 'uses' => 'ProjectLaunchController@update']);

            //模版排期
            //模版
            $api->get('projects/launches/tpl', ['middleware' => ['permission:project.schedule.read'], 'uses' => 'ProjectLaunchTplController@index']);
            $api->post('projects/launches/tpl', ['middleware' => ['permission:project.schedule.create'], 'uses' => 'ProjectLaunchTplController@store']);
            $api->patch('projects/launches/tpl/{projectLaunchTpl}', ['middleware' => ['permission:project.schedule.update'], 'uses' => 'ProjectLaunchTplController@update']);
            //模版子条目
            $api->post('projects/schedules', ['middleware' => ['permission:project.schedule.childCreate'], 'uses' => 'ProjectLaunchTplScheduleController@store']);
            $api->patch('projects/schedules/{projectLaunchTplSchedule}', ['middleware' => ['permission:project.schedule.childUpdate'], 'uses' => 'ProjectLaunchTplScheduleController@update']);

            //节目列表
            $api->get('projects', ['middleware' => ['permission:project.list.read'], 'uses' => 'ProjectController@index']);
            $api->patch('projects', ['middleware' => ['permission:project.list.update'], 'uses' => 'ProjectController@update']);

            //节目模板
            $api->get('project_template', 'ProjectTemplateController@index');


            //广告数据统计
            $api->get('project_ad_log', 'ProjectAdLogController@index');
        });
    });

});