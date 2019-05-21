<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Project\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], static function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], static function ($api) {

        $api->group(['middleware' => 'api.auth', 'model' => 'App\Models\User'], static function ($api) {

            //节目投放
            $api->get('projects/launch', ['middleware' => ['permission:project.item.read'], 'uses' => 'ProjectLaunchController@index']);
            $api->post('projects/launch', ['middleware' => ['permission:project.item.create'], 'uses' => 'ProjectLaunchController@store']);
            $api->patch('projects/launches', ['middleware' => ['permission:project.item.update'], 'uses' => 'ProjectLaunchController@update']);

            //模版排期
            //模版
            $api->get('projects/tpl', ['middleware' => ['permission:project.template.read'], 'uses' => 'ProjectLaunchTplController@index']);
            $api->post('projects/tpl', ['middleware' => ['permission:project.template.create'], 'uses' => 'ProjectLaunchTplController@store']);
            $api->patch('projects/tpl/{tpl}', ['middleware' => ['permission:project.template.update'], 'uses' => 'ProjectLaunchTplController@update']);
            //排期
            $api->get('projects/tpl/{tpl}/schedules', ['middleware' => ['permission:project.schedule.read'],'uses'=>'ProjectLaunchTplScheduleController@index']);
            $api->post('projects/tpl/{tpl}/schedules', ['middleware' => ['permission:project.schedule.create'], 'uses' => 'ProjectLaunchTplScheduleController@store']);
            $api->patch('projects/tpl/{tpl}/schedules/{schedule}', ['middleware' => ['permission:project.schedule.update'], 'uses' => 'ProjectLaunchTplScheduleController@update']);

            //节目列表
            $api->get('projects', ['middleware' => ['permission:project.list.read'], 'uses' => 'ProjectController@index']);
            $api->patch('projects', ['middleware' => ['permission:project.list.update'], 'uses' => 'ProjectController@update']);

        });
    });

});