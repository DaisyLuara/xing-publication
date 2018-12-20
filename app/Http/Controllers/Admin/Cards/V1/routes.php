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
            $api->get('cards/list', 'CardsController@list');

            //获取单个卡券详情
            $api->get('cards/{cards}', 'CardsController@show');

            //更改卡券信息
            $api->patch('cards/{cards}', 'CardsController@update');

            //删除卡券
            $api->delete('cards/{cards}', 'CardstController@destroy');

            //创建卡券
            $api->post('cards/create', 'CardstController@store');

            //修改库存接口
            $api->get('cards/stock','CardsController@stock');

            //上传图片接口
            $api->get('cards/uploadpic','CardsController@uploadpic');

        });
    });

});