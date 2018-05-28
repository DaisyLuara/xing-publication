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

app('Dingo\Api\Exception\Handler')->register(function (Exception $exception) {
    if ($exception instanceof TokenExpiredException) {
        return response()->json('未登录', 401);
    };
});

include app_path('Http/Controllers/Admin/Ad/V1/routes.php');
include app_path('Http/Controllers/Admin/Common/V1/routes.php');
include app_path('Http/Controllers/Admin/Company/V1/routes.php');
//$api->version('v1', [
//    'namespace' => 'App\Http\Controllers\Api\V1',
//    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
//], function ($api) {
//    $api->group([
//        'middleware' => 'api.throttle',//频率限制中间件
//        'limit' => config('api.rate_limits.access.limit'),
//        'expires' => config('api.rate_limits.access.expires'),
//    ], function ($api) {
//
//        $api->post('captchas', 'CaptchasController@store');// 图片验证码
//        $api->get('point_configs/{point_config}', 'PointConfigController@show');//点位配置
//
//        //第三方集成
//        $api->get('login/tower', 'TowerLoginController@redirectToProvider');
//        $api->get('login/tower/callback', 'TowerLoginController@handleProviderCallback');
//
//        // 需要 token 验证的接口
//        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {
//
//            // 当前登录用户信息
//            $api->get('user', 'UsersController@me');
//            $api->patch('user', 'UsersController@update');//patch 部分修改资源，提供部分资源信息
//            $api->put('authorizations/current', 'AuthorizationsController@update');// 刷新token
//            $api->delete('authorizations/current', 'AuthorizationsController@destroy');// 删除token
//

//
//
//            //落地方式配置
//            $api->get('project_ad_launch', 'ProjectAdLaunchController@index');
//            $api->post('project_ad_launch', 'ProjectAdLaunchController@store');
//            $api->patch('project_ad_launch', 'ProjectAdLaunchController@update');
//
//            //授权广告
//            $api->get('wx_third', 'WxThirdController@index');
//            //广告数据统计
//            $api->get('project_ad_log', 'ProjectAdLogController@index');
//

//
//            //产品
//            $api->get('product', 'ProductController@index');
//
//            //节目模板
//            $api->get('project_template', 'ProjectTemplateController@index');
//
//            //节目投放
//            $api->get('projects', 'ProjectController@index');
//            $api->post('projects', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectControllerController@store']);
//            $api->patch('projects', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectControllerController@update']);
//            $api->get('projects/launch', 'ProjectLaunchController@index');
//            $api->post('projects/launch', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectLaunchController@store']);
//            $api->patch('projects/launches', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectLaunchController@update']);
//

//
//            // 权限设置
//            $api->get('system/users', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@index']);
//            $api->get('system/users/{user}', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@show']);
//            $api->post('system/users', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@store']);
//            $api->patch('system/users/{user}', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@update']);
//            $api->get('system/roles', ['middleware' => ['role:super-admin|admin'], 'uses' => 'RolesController@index']);
//


//            //团队
//            $api->post('oauth/token', 'TowerController@refresh');
//

//
//        });
//    });
//
//    $api->group([
//        'middleware' => 'api.throttle',
//        'limit' => config('api.rate_limits.sign.limit'),
//        'expires' => config('api.rate_limits.sign.expires'),
//    ], function ($api) {
//        //管理员登陆
//        $api->post('verificationCodes', 'VerificationCodesController@store'); // 短信验证码
//        $api->post('authorizations', 'AuthorizationsController@store'); //登陆
//
//        //用户登录
//        $api->post('customer/login', 'AuthorizationsController@customerLogin');
//    });
//});
