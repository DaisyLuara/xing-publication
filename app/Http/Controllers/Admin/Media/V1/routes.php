<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Media\V1\Api',//修改test
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], static function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], static function ($api) {
        $api->group(['middleware' => 'api.auth', 'model' => 'App\Models\User'], static function ($api) {
            //publication 普通文件上传
            $api->post('media', 'MediaController@store');
            //publication 大文件上传
            $api->post('media_upload', 'MediaController@create');

            $api->get('media_infos', ['middleware' => ['permission:team.operation.read'], 'uses' => 'MediaInfoController@index']);
            $api->get('media_infos/{media_info}', 'MediaInfoController@show');
            $api->post('media_infos', ['middleware' => ['permission:team.operation.create'], 'uses' => 'MediaInfoController@store']);
            $api->patch('media_infos/{media_info}', ['middleware' => ['permission:team.operation.update'], 'uses' => 'MediaInfoController@update']);
            $api->delete('media_infos', ['middleware' => ['permission:team.operation.delete'], 'uses' => 'MediaInfoController@destroy']);

            //publication 获取七牛上传token
            $api->get('qiniu_oauth', 'QiniuController@oauth');
        });

        $api->group(['middleware' => 'api_sign'], static function ($api) {
            $api->get('qiniu_token','QiniuController@getToken');
            //活动文件上传 需要鉴定
            $api->post('activity_media', 'MediaController@MediaTransformer');
        });

    });

});