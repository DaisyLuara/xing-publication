<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Feedback\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            $api->get('feedback', ['middleware' => ['permission:feedback.feedback.read'], 'uses' => 'FeedbackController@index']);
            $api->get('feedback/{feedback}', ['middleware' => ['permission:feedback.feedback.read'], 'uses' => 'FeedbackController@show']);
            $api->post('feedback', ['middleware' => ['permission:feedback.feedback.create'], 'uses' => 'FeedbackController@store']);
        });
    });

});