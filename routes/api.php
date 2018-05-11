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
    $request = Illuminate\Http\Request::capture();
    return app('App\Exceptions\DingoAPIHandler')->render($request, $exception);
});

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
            $api->patch('user', 'UsersController@update');//patch 部分修改资源，提供部分资源信息

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

            //数据统计
            $api->get('stats', 'FaceCountController@index');
            $api->get('detail', 'FaceCountController@detail');
            $api->get('age_gender', 'FaceLogController@index');

            //广告投放
            $api->get('ad_launch', 'AdLaunchController@index');
            $api->post('ad_launch', 'AdLaunchController@store');
            $api->patch('ad_launch', 'AdLaunchController@update');

            //广告
            $api->get('advertisement', 'AdvertisementController@index');

            //广告主
            $api->get('advertiser', 'AdvertiserController@index');

            //广告行业
            $api->get('ad_trade', 'AdTradeController@index');

            //落地方式配置
            $api->get('project_ad_launch', 'ProjectAdLaunchController@index');

            //授权广告
            $api->get('wx_third', 'WxThirdController@index');

            //设备
            $api->get('push', 'PushController@index');

            //数据报表导出
            $api->get('project_excel','ExcelController@projectExcel');
            $api->get('project_month_excel','ExcelController@projectMonthExcel');

            //节目投放
            $api->get('projects', 'ProjectController@index');
            $api->get('projects/launch', 'ProjectLaunchController@index');
            $api->get('staffs', 'ArUserController@index');
            $api->post('projects/launch', 'ProjectLaunchController@store');
            $api->patch('projects/launches', 'ProjectLaunchController@update');

            //远程搜索
            $api->get('areas/query', 'QueryController@areaQuery');//区域搜索
            $api->get('markets/query', 'QueryController@marketQuery');//商场搜索
            $api->get('points/query', ['middleware' => ['role:super-admin|admin'], 'uses' => 'QueryController@pointQuery']);//点位查询
            $api->get('launches/tpl/query', ['middleware' => ['role:super-admin|admin'], 'uses' => 'QueryController@launchTplQuery']);//点位模板查询
            $api->get('ad_trade/query', 'QueryController@adTradeQuery');//广告行业搜索
            $api->get('advertiser/query', 'QueryController@advertiserQuery');//广告主搜索
            $api->get('advertisement/query', 'QueryController@advertisementQuery');//广告搜索
            $api->get('projects/query', 'QueryController@projectQuery');

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

        });
    });
});
