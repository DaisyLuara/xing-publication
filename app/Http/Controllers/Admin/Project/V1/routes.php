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
            $api->get('projects', 'ProjectController@index');
//            $api->post('projects', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectController@store']);
            $api->patch('projects', ['middleware' => ['role:super-admin|admin|user|project-manager|market_owner'], 'uses' => 'ProjectController@update']);
            $api->get('projects/launch', 'ProjectLaunchController@index');
            $api->post('projects/launch', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectLaunchController@store']);
            $api->patch('projects/launches', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectLaunchController@update']);
            $api->get('projects/launches/tpl', 'ProjectLaunchTplController@index');
            $api->post('projects/launches/tpl', 'ProjectLaunchTplController@store');
            $api->patch('projects/launches/tpl/{projectLaunchTpl}', 'ProjectLaunchTplController@update');
            $api->post('projects/schedules', 'ProjectLaunchTplScheduleController@store');
            $api->patch('projects/schedules/{projectLaunchTplSchedule}', 'ProjectLaunchTplScheduleController@update');
            $api->get('projects/launches/tpl', 'ProjectLaunchTplController@index');

            //节目模板
            $api->get('project_template', 'ProjectTemplateController@index');


            //广告数据统计
            $api->get('project_ad_log', 'ProjectAdLogController@index');
        });
    });

});