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
        //话题列表
        $api->get('topics', 'TopicsController@index');
        //某个用户的话题列表
        $api->get('users/{user}/topics', 'TopicsController@userIndex');
        //话题详情
        $api->get('topics/{topic}', 'TopicsController@show');

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
            // 删除话题
            $api->delete('topics/{topic}', 'TopicsController@destroy');


            // 发布回复
            $api->post('topics/{topic}/replies', 'RepliesController@store');
            // 删除回复
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy');

            //消息通知
            $api->get('user/notifications', 'NotificationsController@index');
            // 通知统计
            $api->get('user/notifications/stats', 'NotificationsController@stats');
            // 标记消息通知为已读
            $api->patch('user/read/notifications', 'NotificationsController@read');


            // 当前登录用户权限
            $api->get('user/permissions', 'PermissionsController@index');

            //数据统计
            $api->get('stats', 'FaceCountController@index');

            //节目
            $api->get('projects', 'ProjectController@index');


            //星视度用户
            $api->get('staffs', 'ArUserController@index');

            // 管理员添加用户
            $api->post('users', ['middleware' => ['role:super-admin'], 'uses' => 'UsersController@store']);

            // 获取可用角色列表
            $api->get('roles', ['middleware' => ['role:super-admin|admin'], 'uses' => 'RolesController@index']);
        });
    });
});
