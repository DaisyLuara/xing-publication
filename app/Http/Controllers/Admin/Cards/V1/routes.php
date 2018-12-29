<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Cards\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
            //批量查询卡券列表
            $api->get('cards/list', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@index']);
            //获取单个卡券详情
            $api->get('cards/show', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@show']);
            //更改卡券信息
            $api->patch('cards/update', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@update']);
            //删除卡券
            $api->delete('cards/delete', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@destroy']);
            //创建卡券
            $api->post('cards/create', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@store']);
            //获取用户授权信息
            $api->get('cards/getinfo', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@getInfo']);
            //修改库存接口
            $api->post('cards/stock', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@stock']);
            //上传图片接口
            $api->post('cards/uploadPic', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@uploadPic']);
            //上传缩略图
            $api->post('cards/uploadThumb', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@uploadThumb']);
            //上传图文消息
            $api->post('cards/uploadArticle', ['middleware' => ['permission:wechat_card'], 'uses' => 'CardsController@uploadArticle']);
        });
    });

});