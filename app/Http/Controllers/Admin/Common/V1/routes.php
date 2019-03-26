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
            $api->get('open/project/policy', 'ProjectController@show');//优惠券策略
            $api->any('open/coupon/batches', 'CouponController@generateCouponBatch');//按策略获取优惠券规则详情
            $api->any('open/coupon/batches/stock', 'CouponController@generateCouponBatchAndDecrement');//按策略获取优惠券规则，同时减少库存
            $api->get('open/coupon_batches', 'CouponBatchController@index');//按策略优惠券规则列表

            $api->post('open/coupon/batches/{couponBatch}', 'CouponController@getCouponBatch');//获取优惠券规则
            $api->post('open/coupons/{couponBatch}', 'CouponController@generateCoupon');//发送优惠券
            $api->post('open/user/coupon', 'CouponController@getUserCoupon');//获取用户优惠券

        });

        $api->post('open/redpack/{code}', 'RedPackController@store');

        //小程序优惠券接口
        $api->group(['middleware' => 'api_sign', 'prefix' => 'mini'], function ($api) {
            $api->get('user/coupons', 'MiniCouponController@couponIndex');//优惠券列表
            $api->get('user/coupons/{code}', 'MiniCouponController@couponShow');//优惠券详情

            $api->post('user/coupon_batch/{couponBatch}', 'MiniCouponController@store');//发送优惠券
            $api->post('user/random/coupon_batch', 'MiniCouponController@randomSending');//随机发送优惠券
            $api->any('coupon/batches', 'MiniCouponController@couponBatchesIndex');//优惠券规则列表
            $api->get('coupon/batches/{couponBatch}', 'MiniCouponController@couponBatchShow');//优惠券规则详情

            $api->get('activities', 'ActivitiesController@index');//活动列表
        });

        //淘宝接口
        $api->group(['prefix' => 'tmall'], function ($api) {
            $api->get('user/coupon_batch/{couponBatch}', 'TaobaoCouponController@show');//获取用户的淘宝优惠券
            $api->post('user/coupon_batch/{couponBatch}', 'TaobaoCouponController@store');//发送淘宝优惠券
            $api->post('user/coupon', 'TaobaoCouponController@update');//核销
        });

        $api->group(['middleware' => 'api_sign'], function ($api) {
            $api->get('device/params', 'FileUploadController@show');//获取大屏参数
            $api->post('images', 'ImagesController@store');//上传图片
            $api->post('temp/customer', 'TempCustomerController@store');//临时用户保存
            $api->get('temp/customer/all', 'TempCustomerController@all');//临时用户总数
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
            $api->get('projects/query', 'QueryController@projectQuery');//节目搜索
            $api->get('team_projects/query', 'QueryController@teamProjectQuery');//团队节目搜索
            $api->get('coupon_batch/query', 'QueryController@couponBatchQuery');
            $api->get('company/query', 'QueryController@companyQuery');
            $api->get('staffs', 'QueryController@arUserQueryIndex');
            $api->get('scene/query', 'QueryController@sceneQueryIndex');
            $api->get('policy/query', 'QueryController@policyQuery');
            $api->get('contract/query', 'QueryController@contractQuery');
            $api->get('invoice_kind/query', 'QueryController@invoiceKindQuery');
            $api->get('goods_service/query', 'QueryController@goodsServiceQuery');
            $api->get('bd_manager/query', 'QueryController@bdManagerQuery');
            $api->get('bd_users/query', 'QueryController@bdAndBdManagerQuery');
            $api->get('legal_manager/query', 'QueryController@legalManagerQuery');
            $api->get('invoice_company/query', 'QueryController@invoiceCompanyQuery');
            $api->get('payment_payee/query', 'QueryController@paymentPayeeQuery');
            $api->get('receive_date/query', 'QueryController@receiveDateQuery');
            $api->get('customer/query', 'QueryController@customerQuery');
            $api->get('user/query', 'QueryController@userQuery');

            $api->get('user/permission/query', 'QueryController@userPermissionQuery');
            $api->get('demand_application/query', 'QueryController@demandApplicationQuery');

            $api->get('team_rate/query', 'QueryController@teamRateQuery');
            $api->get('attribute/query', 'QueryController@attributeQuery');
            $api->get('permission/query', 'QueryController@permissionQuery');
            $api->get('role/query', 'QueryController@roleQuery');
            $api->get('erp_warehouse/query', 'QueryController@warehouseQuery');//流程管理ERP仓库搜索
            $api->get('erp_attribute/query', 'QueryController@erpAttributeQuery');//流程管理ERP商品属性搜索
            $api->get('erp_supplier/query', 'QueryController@erpSupplierQuery');//供应商搜索
            $api->get('erp_sku/query', 'QueryController@erpSkuQuery');//sku下拉列表
            $api->get('erp_location/query', 'QueryController@erpLocationQuery');//库位下拉列表
            $api->get('stores/query', 'QueryController@storeQuery');//商户搜索
            $api->get('company/markets/query', 'QueryController@marketConfigQuery');//公司搜索
            $api->get('playing_types/query', 'QueryController@playingTypeQuery');//玩法配置列表
            $api->get('cost_kind/query', 'QueryController@costKindQuery');
            $api->get('customers/query', 'QueryController@adminCustomersQuery');//所有联系人查询
            $api->get('customers/role/{role_name}/query', 'QueryController@adminCustomersQueryByRole'); //某个角色的customer
            $api->get('authorized_projects/query', 'QueryController@authorizedProjectQuery');//已授权节目列表
            $api->get('authorized_points/query', 'QueryController@authorizedPointQuery');//已授权点位列表
            $api->get('authorized_policies/query', 'QueryController@authorizedPolicyQuery');//已授权策略列表
            $api->get('authorized_customers/query', 'QueryController@authorizedCustomerQuery');//已授权策略列表


            //消息通知
            $api->get('user/notifications', 'NotificationsController@index');
            $api->get('user/notifications/stats', 'NotificationsController@stats');
            $api->patch('user/read/notifications', 'NotificationsController@read');
            $api->delete('user/notifications', 'NotificationsController@destroy');
            $api->get('user/activities', 'ActivityLogController@index');

            $api->get('export', 'ExportController@store');//导出

            $api->get('qiniu_oauth', 'QiniuController@oauth');
        });

        $api->get('website/fcpe', 'WebsiteController@getFCpe');
        $api->post('website/visitor', 'WebsiteController@storeVisitor');

    });

});