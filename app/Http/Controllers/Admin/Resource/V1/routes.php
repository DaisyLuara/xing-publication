<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Resource\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], static function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], static function ($api) {
        $api->group(['middleware' => 'api.auth', 'model' => 'App\Models\User'], function ($api) {

            //公司资源审核
            $api->get('company_media', ['middleware' => ['permission:resource.company.read'], 'uses' => 'CompanyMediaController@index']);
            $api->patch('company_media/audit/{media}', ['middleware' => ['permission:resource.company.audit'], 'uses' => 'CompanyMediaController@audit']);

            //活动资源审核
            $api->get('activity_media', ['middleware' => ['permission:resource.activity.read'], 'uses' => 'ActivityMediaController@index']);
            $api->patch('activity_media/audit', ['middleware' => ['permission:resource.activity.audit'], 'uses' => 'ActivityMediaController@massAudit']);
        });
        $api->group(['middleware' => 'api_sign'], static function ($api) {
            //活动文件上传
            $api->get('qiniu_token', 'ActivityMediaController@getToken');
            $api->post('activity_media', 'ActivityMediaController@create');
        });
    });

});