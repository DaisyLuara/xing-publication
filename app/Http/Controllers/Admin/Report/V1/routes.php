<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Report\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            //首页数据
            $api->post('home_chart_data', ['middleware' => ['permission:home.item.read'], 'uses' => 'ChartDataController@chart']);

            //人数
            $api->get('stats', ['middleware' => ['permission:report.detail.read'], 'uses' => 'ChartDataController@index']);
            $api->post('chart_data', ['middleware' => ['permission:report.detail.read'], 'uses' => 'ChartDataController@chart']);

            //人次
            $api->get('times_stats', ['middleware' => ['permission:report.detail.read'], 'uses' => 'ChartDataTimesController@index']);
            $api->post('chart_data_times', ['middleware' => ['permission:report.detail.read'], 'uses' => 'ChartDataTimesController@chart']);

            //数据导出
            $api->get('chart_data/export', ['middleware' => ['permission:report.detail.download'], 'uses' => 'ChartDataController@export']);
        });
    });

});