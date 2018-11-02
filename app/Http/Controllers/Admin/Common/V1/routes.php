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

        //h5页面优惠券
        $api->group(['middleware' => 'api_sign'], function ($api) {
            $api->get('open/project/policy', 'ProjectController@show');//获取抽奖规则
            $api->any('open/coupon/batches', 'CouponController@generateCouponBatch');//抽奖

            $api->post('open/coupon/batches/{couponBatch}', 'CouponController@getCouponBatch');//获取优惠券规则
            $api->post('open/coupons/{couponBatch}', 'CouponController@generateCoupon');//发送优惠券
            $api->post('open/user/coupon', 'CouponController@getUserCoupon');//获取用户优惠券
        });

        //小程序优惠券接口
        $api->group(['middleware' => 'api_sign', 'prefix' => 'mini'], function ($api) {
            $api->get('user/coupons', 'MiniCouponController@couponIndex');//优惠券列表
            $api->get('user/coupons/{code}', 'MiniCouponController@couponShow');//优惠券详情

            $api->post('user/coupon_batch/{couponBatch}', 'MiniCouponController@store');//发送优惠券
            $api->any('coupon/batches', 'MiniCouponController@couponBatchesIndex');//优惠券规则列表
        });

        //淘宝接口
        $api->group(['prefix' => 'tmall'], function ($api) {
            $api->get('user/coupon_batch/{couponBatch}', 'TaobaoCouponController@show');//获取用户的淘宝优惠券
            $api->post('user/coupon_batch/{couponBatch}', 'TaobaoCouponController@store');//发送淘宝优惠券
            $api->post('user/coupon', 'TaobaoCouponController@update');//核销
        });

        //获取大屏参数
        $api->group(['middleware' => 'api_sign'], function ($api) {
            $api->get('device/params', 'FileUploadController@show');
        });


        $api->get('s/{url_path}', 'ShortUrlController@redirect');//短链接跳转
        $api->post('open/short_urls', 'ShortUrlController@store');//短链接生成

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
            $api->get('policy/query', 'QueryController@policyQuery');
            $api->get('contract/query', 'QueryController@contractQuery');
            $api->get('goods_service/query', 'QueryController@goodsServiceQuery');
            $api->get('bd_manager/query', 'QueryController@bdManagerQuery');
            $api->get('legal_manager/query', 'QueryController@legalManagerQuery');

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