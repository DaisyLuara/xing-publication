<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Auth\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        //第三方集成
        $api->get('login/tower', 'TowerLoginController@redirectToProvider');
        $api->get('login/tower/callback', 'TowerLoginController@handleProviderCallback');
        $api->post('user/bind/{driver}', 'AuthorizationsController@bind');

        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            $api->post('oauth/token', 'TowerController@refresh');

            $api->put('authorizations/current', 'AuthorizationsController@update');// 刷新token
            $api->delete('authorizations/current', 'AuthorizationsController@destroy');// 删除token
        });
    });


    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        //管理员登陆
        $api->post('verificationCodes', 'VerificationCodesController@store'); // 短信验证码
        $api->post('authorizations', 'AuthorizationsController@store'); //登陆
        $api->post('system_skip', ['middleware' => ['api.auth', 'CrossRequest'], 'uses' => 'AuthorizationsController@systemSkip']);

        //用户登录
        $api->post('customer/login', 'AuthorizationsController@customerLogin');
    });

});