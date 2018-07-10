<?php
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin\Common\V1\Api',
    'middleware' => ['serializer:array', 'bindings'] //api返回数据切换. Fractal 组件默认提供  DataArraySerializer ArraySerializer
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',//频率限制中间件
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {

        $api->post('captchas', 'CaptchasController@store');// 图片验证码

        $api->get('coupon/status', 'CouponController@getCouponStatus');
        $api->post('coupon', 'CouponController@createCoupon');
        $api->get('coupons/{coupon_id}', 'CouponController@getCoupon');

        $api->group(['middleware' => "api.auth", 'model' => 'App\Models\User'], function ($api) {

            //远程搜索
            $api->get('areas/query', 'QueryController@areaQuery');//区域搜索
            $api->get('markets/query', 'QueryController@marketQuery');//商场搜索
            $api->get('points/query', 'QueryController@pointQuery');//点位查询
            $api->get('launches/tpl/query', 'QueryController@launchTplQuery');//点位模板查询
            $api->get('ad_trade/query', 'QueryController@adTradeQuery');//广告行业搜索
            $api->get('advertiser/query', 'QueryController@advertiserQuery');//广告主搜索
            $api->get('advertisement/query', 'QueryController@advertisementQuery');//广告搜索
            $api->get('projects/query', 'QueryController@projectQuery');
            $api->get('coupon_batch/query', 'QueryController@couponBatchQuery');
            $api->get('company/query', 'QueryController@companyQuery');
            $api->get('staffs', 'QueryController@arUserQueryIndex');
            $api->get('scene/query', 'QueryController@sceneQueryIndex');

            //消息通知
            $api->get('user/notifications', 'NotificationsController@index');
            $api->get('user/notifications/stats', 'NotificationsController@stats');
            $api->patch('user/read/notifications', 'NotificationsController@read');
            $api->get('user/activities', 'ActivityLogController@index');

            // 图片资源
            $api->post('images', 'ImagesController@store');

            //数据统计
            $api->get('stats', 'ChartDataController@index');//列表
            $api->post('chart_data', 'ChartDataController@chart');//图表
            $api->get('export', 'ExportController@store');//导出
        });
    });

});