<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store');
        // 用户注册
        $api->post('users', 'UsersController@store');
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store');
        // 登录
        $api->post('authorizations', 'AuthorizationsController@store');
        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy');

        // 游客可以访问的接口
        $api->get('categories', 'CategoriesController@index');

        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function ($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me');
            // 图片资源
            $api->post('images', 'ImagesController@store');
            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update');//patch 部分修改资源，提供部分资源信息
            // 发布话题
            $api->post('topics', 'TopicsController@store');
            // 修改话题
            $api->patch('topics/{topic}', 'TopicsController@update');

        });
    });
});
