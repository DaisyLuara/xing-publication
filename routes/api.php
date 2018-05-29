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

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->post('captchas', 'CaptchasController@store');// 图片验证码
        $api->get('point_configs/{point_config}', 'PointConfigController@show');//点位配置

        //第三方集成
        $api->get('login/tower', 'TowerLoginController@redirectToProvider');
        $api->get('login/tower/callback', 'TowerLoginController@handleProviderCallback');

        // 需要 token 验证的接口
        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            // 当前登录用户信息
            $api->get('user', 'UsersController@me');
            $api->patch('user', 'UsersController@update');//patch 部分修改资源，提供部分资源信息
            $api->put('authorizations/current', 'AuthorizationsController@update');// 刷新token
            $api->delete('authorizations/current', 'AuthorizationsController@destroy');// 删除token

            // 图片资源
            $api->post('images', 'ImagesController@store');

            // 话题
            $api->post('topics', 'TopicsController@store');
            $api->patch('topics/{topic}', 'TopicsController@update');
            $api->delete('topics/{topic}', 'TopicsController@destroy');

            // 回复
            $api->post('topics/{topic}/replies', 'RepliesController@store');
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy');

            //消息通知
            $api->get('user/notifications', 'NotificationsController@index');
            $api->get('user/notifications/stats', 'NotificationsController@stats');
            $api->patch('user/read/notifications', 'NotificationsController@read');
            $api->get('user/activities', 'ActivityLogController@index');


            //广告投放
            $api->get('ad_launch', 'AdLaunchController@index');
            $api->post('ad_launch', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'AdLaunchController@store']);
            $api->patch('ad_launch', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'AdLaunchController@update']);

            //广告
            $api->get('advertisement', 'AdvertisementController@index');
            $api->post('advertisement', 'AdvertisementController@store');
            $api->patch('advertisement', 'AdvertisementController@update');

            //广告主
            $api->get('advertiser', 'AdvertiserController@index');
            $api->post('advertiser', 'AdvertiserController@store');
            $api->patch('advertiser', 'AdvertiserController@update');

            //广告行业
            $api->get('ad_trade', 'AdTradeController@index');
            $api->post('ad_trade', 'AdTradeController@store');
            $api->patch('ad_trade', 'AdTradeController@update');

            //落地方式配置
            $api->get('project_ad_launch', 'ProjectAdLaunchController@index');
            $api->post('project_ad_launch', 'ProjectAdLaunchController@store');
            $api->patch('project_ad_launch', 'ProjectAdLaunchController@update');

            //授权广告
            $api->get('wx_third', 'WxThirdController@index');
            //广告数据统计
            $api->get('project_ad_log', 'ProjectAdLogController@index');

            //设备
            $api->get('push', 'PushController@index');
            $api->get('point/map', 'PointController@map');

            //产品
            $api->get('product', 'ProductController@index');

            //节目模板
            $api->get('project_template', 'ProjectTemplateController@index');

            //节目投放
            $api->get('projects', 'ProjectController@index');
            $api->post('projects', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectControllerController@store']);
            $api->patch('projects', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectControllerController@update']);
            $api->get('projects/launch', 'ProjectLaunchController@index');
            $api->post('projects/launch', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectLaunchController@store']);
            $api->patch('projects/launches', ['middleware' => ['role:super-admin|admin|user|project-manager'], 'uses' => 'ProjectLaunchController@update']);
            $api->get('staffs', 'ArUserController@index');

            //远程搜索
            $api->get('areas/query', 'QueryController@areaQuery');//区域搜索
            $api->get('markets/query', 'QueryController@marketQuery');//商场搜索
            $api->get('points/query', 'QueryController@pointQuery');//点位查询
            $api->get('launches/tpl/query', 'QueryController@launchTplQuery');//点位模板查询
            $api->get('ad_trade/query', 'QueryController@adTradeQuery');//广告行业搜索
            $api->get('advertiser/query', 'QueryController@advertiserQuery');//广告主搜索
            $api->get('advertisement/query', 'QueryController@advertisementQuery');//广告搜索
            $api->get('projects/query', 'QueryController@projectQuery');
            $api->get('scene/query', 'QueryController@sceneQueryIndex');

            // 权限设置
            $api->get('system/users', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@index']);
            $api->get('system/users/{user}', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@show']);
            $api->post('system/users', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@store']);
            $api->patch('system/users/{user}', ['middleware' => ['role:super-admin|admin'], 'uses' => 'AdminUsersController@update']);
            $api->get('system/roles', ['middleware' => ['role:super-admin|admin'], 'uses' => 'RolesController@index']);

            //公司管理
            $api->get('companies', 'AdminCompaniesController@index');
            $api->get('companies/{company}', 'AdminCompaniesController@show');
            $api->post('companies', ['middleware' => ['role:user'], 'uses' => 'AdminCompaniesController@store']);
            $api->patch('companies/{company}', ['middleware' => ['role:user'], 'uses' => 'AdminCompaniesController@update']);

            //公司客户管理
            $api->get('companies/{company}/customers', 'AdminCustomersController@index');
            $api->get('companies/{company}/customers/{customer}', 'AdminCustomersController@show');
            $api->post('companies/{company}/customers', ['middleware' => ['permission:company'], 'uses' => 'AdminCustomersController@store']);
            $api->patch('companies/{company}/customers/{customer}', ['middleware' => ['permission:company'], 'uses' => 'AdminCustomersController@update']);

            //团队
            $api->post('oauth/token', 'TowerController@refresh');

            //数据统计
            $api->get('stats', 'ChartDataController@index');//列表
            $api->post('chart_data', 'ChartDataController@chart');//图表
            $api->get('export', 'ExportController@store');

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

        //用户登录
        $api->post('customer/login', 'AuthorizationsController@customerLogin');
    });

//    //公众号
//    $api->any('/wx/officialAccount/oauth', 'WeChatController@oauth');
//    $api->any('/wx/officialAccount/oauth/callback', 'WeChatController@callback');
//
//    $api->any('/wx/officialAccount/message', 'WeChatController@message');
//    $api->get('/wx/officialAccount/menu', 'WeChatController@menu');
//    $api->get('/wx/officialAccount/qrcode', 'WeChatController@qrCode');

    //第三方平台
    $api->any('/openPlatform/events', 'WeChatController@events');

    $api->any('/openPlatform/preAuthUrl', 'WeChatController@preAuthUrl');

    $api->any('/openPlatform/authorization', 'WeChatController@authorization');

});
