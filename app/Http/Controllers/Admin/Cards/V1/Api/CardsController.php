<?php
namespace App\Http\Controllers\Admin\Cards\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EasyWeChat;

class CardsController extends Controller
{

    //获取卡片实例
    protected function getCard($authorizer_id)
    {
        $app = EasyWeChat::openPlatform();
        componentVerify($app);
        $official_account = getOfficialAccount($authorizer_id, $app);
        $card = $official_account->card;

        return $card;

    }

    protected function getMaterial($authorizer_id)
    {
        $app = EasyWeChat::openPlatform();
        componentVerify($app);
        $official_account = getOfficialAccount($authorizer_id, $app);
        $material = $official_account->material;

        return $material;
    }

    //上传图片
    public function uploadPic(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $material = $this->getMaterial($authorizer_id);
        $path = $request->has('path')  ? $request->get('path') : '';

        $result = $material->uploadImage($path);

        return $result;
    }

    //上传缩略图
    public function uploadThumb(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $material = $this->getMaterial($authorizer_id);
        $path = $request->has('path')  ? $request->get('path') : '';

        $result = $material->uploadThumb($path);

        return $result;

    }

    //上传图文消息
    public function uploadArticle(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $material = $this->getMaterial($authorizer_id);
        $title = $request->has('title')  ? $request->get('title') : '';
        $mediaId = $request->has('media_id') ? $request->get('media_id') : '';

        $article = new EasyWeChat\Kernel\Messages\Article([
            'title' => $title,
            'thumb_media_id' => $mediaId,
        ]);

        $result = $material->uploadArticle($article);

        return $result;
    }

    //查询单个卡券列表
    public function show(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $cardId = $request->has('card_id') ? $request->get('card_id') : '';

        $cardInfo = $card->get($cardId);

        return $cardInfo;
    }

    //批量查询卡券列表
    public function index(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $statusList = $request->has('status_list') ? $request->get('status_list') : '';

        $cardlist = $card->list($offset = 0, $count = 50, $statusList);

        return $cardlist;

    }


    //删除卡券
    public function destroy(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $cardId = $request->has('card_id') ? $request->get('card_id') : '';

        return $card->delete($cardId);

    }

    //修改库存
    public function stock(Request $request)
    {

        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $cardId = $request->has('card_id') ? $request->get('card_id') : '';

        $increaseStockValue = $request->has('increase_stock_value') ? $request->get('increase_stock_value') : '0';
        $reduceStockValue = $request->has('reduce_stock_value') ? $request->get('reduce_stock_value') : '0';

        if($increaseStockValue >= 1) {
            $result = $card->increaseStock($cardId, $increaseStockValue);
        }

        if($reduceStockValue >= 1 ) {
            $result = $card->reduceStock($cardId, $reduceStockValue);
        }

        return $result;
    }

    //创建卡券
    public function store(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $cardType = $request->has('card_type') ? $request->get('card_type') : 'GENERAL_COUPON';

        //base_info 必填字段
        $logoUrl = $request->has('logo_url') ? $request->get('logo_url') : 'http://mmbiz.qpic.cn/mmbiz_png/oyPZqmUaXnXtOwmk1ZR3suMuktciaZsNMuZGRFXSeibSgH9mV4kUblNbt1iaibA2vvNwOibnF5jphvgJmG3dEf6k4sQ/0?wx_fmt=png';
        $codeType = $request->has('code_type') ? $request->get('code_type') : 'CODE_TYPE_TEXT';
        $brandName = $request->has('brand_name') ? $request->get('brand_name') : '星视度';
        $title = $request->has('title') ? $request->get('title') : '';
        $color = $request->has('color') ? $request->get('color') : '';
        $notice = $request->has('notice') ? $request->get('notice') : '';
        $description = $request->has('description') ? $request->get('description') : '';
//        $sku = $request->has('sku') ? $request->get('sku') : '';
        $quantity = $request->has('quantity') ? $request->get('quantity') : '';

//        $dateInfo = $request->has('date_info') ? $request->get('date_info') : '';

        $type = $request->has('type') ? $request->get('type') : '';

        $beginTimestamp = $request->has('begin_timestamp') ? $request->get('begin_timestamp') : '';
        $endTimstamp = $request->has('end_timestamp') ? $request->get('end_timestamp') : '';



        $fixedTerm = $request->has('fixed_term') ?  $request->get('fixed_term') : '';
        $fixedBeginTerm = $request->has('fixed_begin_term') ?  $request->get('fixed_begin_term') : '';



        //        $fixedBeginTerm = $request->has('fixed_begin_term') ?  $request->get('fixed_begin_term') : '';


        //base_info 非必填字段

        $useCustomCode = $request->has('use_custom_code') ?  $request->get('use_custom_code') : 'false';

//        $getCustomCodeMode = $request->has('get_custom_code_mode') ? $request->get('get_custom_code_mode') : '';

        $bindOpenid = $request->has('bind_openid') ? $request->get('bind_openid') : '';
        $servicePhone = $request->has('service_phone') ?  $request->get('service_phone') : '';
        $locationIdList = $request->has('location_id_list') ? $request->get('location_id_list') : '';
        $useAllLocations = $request->has('use_all_locations') ? $request->get('use_all_locations') : 'true';
        $centerTitle = $request->has('center_title') ? $request->get('center_title') : '';
        $centerSubTitle = $request->has('center_sub_title') ? $request->get('center_sub_title') : '';
        $centerUrl = $request->has('center_url') ? $request->get('center_url') : '';
        $centerAppBrandUserName = $request->has('center_app_brand_user_name') ? $request->get('center_app_brand_user_name') : '';
        $centerAppBrandPass = $request->has('center_app_brand_pass') ? $request->get('center_app_brand_pass') : '';
        $customUrlName = $request->has('custom_url_name') ? $request->get('custom_url_name') : '';
        $customUrl = $request->has('custom_url') ? $request->get('custom_url') : '';
        $customUrlSubTitle = $request->has('custom_url_sub_title') ? $request->get('custom_url_sub_title') : '';
        $customAppBrandUserName = $request->has('custom _app_brand_user_name') ? $request->get('custom _app_brand_user_name') : '';
        $customAppBrandPass = $request->has('custom _app_brand_pass') ? $request->get('custom _app_brand_pass') : '';
        $promotionUrlName = $request->has('promotion_url_name') ? $request->get('promotion_url_name') : '';
        $promotionUrl= $request->has('promotion_url') ? $request->get('promotion_url') : '';
        $promotionUrlSubTitle  = $request->has('promotion_url_sub_title') ? $request->get('promotion_url_sub_title') : '';
        $promotionAppBrandUserName = $request->has('promotion _app_brand_user_name') ? $request->get('promotion _app_brand_user_name') : '';
        $promotionAppBrandPass = $request->has('promotion _app_brand_pass') ? $request->get('promotion _app_brand_pass') : '';
        $getLimit = $request->has('get_limit') ? $request->get('get_limit') : '';
        $useLimit = $request->has('use_limit') ? $request->get('use_limit') : '';
        $canShare = $request->has('can_share') ? $request->get('can_share') : '';
        $canGiveFriend = $request->has('can_give_friend') ? $request->get('can_give_friend') : '';




        //不同优惠券对应的额外字段

        //团购券
        $deal_detail = $request->has('deal_detail') ? $request->get('deal_detail') : '';

        //代金券
        $leastCost = $request->has('least_cost') ? $request->get('least_cost') : ''; //代金券专用，表示起用金额（单位为分）,如果无起用门槛则填0。
        $reduceCost = $request->has('reduce_cost') ? $request->get('reduce_cost') : '';//代金券专用，表示减免金额。（单位为分）

        //折扣券,折扣券专用，表示打折额度（百分比）。填30就是七折
        $discount = $request->has('discount') ? $request->get('discount') : '';

        //兑换券,兑换券专用，填写兑换内容的名称。
        $gift = $request->has('gift') ? $request->get('gift') : '';

        //优惠券专用，填写优惠详情。
        $defaultDetail = $request->has('default_detail') ? $request->get('default_detail') : '';


        //Advanced_info（卡券高级信息）字段
//        $advancedInfo = $request->has('advanced_info') ? $request->get('advanced_info') : '';
//        $useCondition = $request->has('use_condition') ? $request->get('use_condition') : '';
        $acceptCategory = $request->has('accept_category') ? $request->get('accept_categoryn') : '';
        $rejectCategory = $request->has('reject_category') ? $request->get('reject_category') : '';





        $attributes = [
            'base_info' => [
                'logo_url' => $logoUrl,
                'brand_name' => $brandName,
                'code_type' => $codeType,
                'title' => $title,
                'color' => $color,
                'notice' => $notice,
                'service_phone' => $servicePhone,
                'description' => $description,
                'date_info' => [
                    'type'=>$type,
                    'begin_timestamp'=>$beginTimestamp,
                    'end_timstamp'=>$endTimstamp,
                    'fixed_term'=>$fixedTerm,
                    'fixed_begin_term'=>$fixedBeginTerm,
                ],
                'sku'=> [
                    'quantity'=>$quantity,
                ],
                'use_limit' => $useLimit,
                'get_limit' => $getLimit,
                'use_custom_code' => $useCustomCode,
                'bind_openid' => $bindOpenid,
                'can_share'=> $canShare,
                'can_give_friend'=> $canGiveFriend,
//                'location_id_list'=> $locationIdList,
                'use_all_locations' => $useAllLocations,
                'center_title'=> $centerTitle,
                'center_sub_title' => $centerSubTitle,
                'center_url' => $centerUrl,
                'custom_url_name' => $customUrlName,
                'custom_url' => $customUrl,
                'custom_url_sub_title' => $customUrlSubTitle,
                'promotion_url_name' => $promotionUrlName,
                'promotion_url' => $promotionUrl,
                'promotion_url_sub_title' => $promotionUrlSubTitle,
                'promotion_app_brand_user_name' => $promotionAppBrandUserName,
                'promotion_app_brand_pass' => $promotionAppBrandPass,
                'center_app_brand_user_name'=> $centerAppBrandUserName,
                'center_app_brand_pass'=>$centerAppBrandPass,
                'custom _app_brand_user_name'=>$customAppBrandUserName,
                'custom _app_brand_pass'=>$centerAppBrandPass,
                //...
            ],
            'advanced_info' => [
                'use_condition' => [
                    'accept_category' => $acceptCategory,
                    'reject_category' => $rejectCategory,
                    'can_use_with_other_discount' => true,
                ],
                'abstract' => [

                ],
                'text_image_list' => [

                ],
                'time_limit' => [

                ],
                'business_service' => [

                ],

            ],
            'deal_detail' => $deal_detail,
            'least_cost' => $leastCost,
            'reduce_cost' => $reduceCost,
            'discount' => $discount,
            'gift' => $gift,
            'default_detail' => $defaultDetail,
            //...
        ];

        $result = $card->create($cardType, $attributes);

        return $result;


    }

    //更改卡券信息
    public  function update(Request $request)
    {

        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';

        $card = $this->getCard($authorizer_id);

        $cardType = $request->has('card_type') ? $request->get('card_type') : 'GENERAL_COUPON';

        $cardId = $request->has('card_id') ? $request->get('card_id'):'';

        //base_info 必填字段

        $logoUrl = $request->has('logo_url') ? $request->get('logo_url') : '';
        $codeType = $request->has('code_type') ? $request->get('code_type') : '';
        $brandName = $request->has('brand_name') ? $request->get('brand_name') : '';
        $title = $request->has('title') ? $request->get('title') : '';
        $color = $request->has('color') ? $request->get('color') : '';
        $notice = $request->has('notice') ? $request->get('notice') : '';
        $description = $request->has('description') ? $request->get('description') : '';
//        $sku = $request->has('sku') ? $request->get('sku') : '';
        $quantity = $request->has('quantity') ? $request->get('quantity') : '';

        $dateInfo = $request->has('date_info') ? $request->get('date_info') : '';

        $type = $request->has('type') ? $request->get('type') : '';

        $beginTimestamp = $request->has('begin_timestamp') ? $request->get('begin_timestamp') : '';
        $endTimstamp = $request->has('end_timestamp') ? $request->get('end_timestamp') : '';
        $fixedTerm = $request->has('fixed_term') ?  $request->get('fixed_term') : '';
        $fixedBeginTerm = $request->has('fixed_begin_term') ?  $request->get('fixed_begin_term') : '';



        //$fixedBeginTerm = $request->has('fixed_begin_term') ?  $request->get('fixed_begin_term') : '';


        //base_info 非必填字段

        $useCustomCode = $request->has('use_custom_code') ?  $request->get('use_custom_code') : 'false';

//        $getCustomCodeMode = $request->has('get_custom_code_mode') ? $request->get('get_custom_code_mode') : '';

        $bindOpenid = $request->has('bind_openid') ? $request->get('bind_openid') : '';
        $servicePhone = $request->has('service_phone') ?  $request->get('service_phone') : '';
        $locationIdList = $request->has('location_id_list') ? $request->get('location_id_list') : '';
        $useAllLocations = $request->has('use_all_locations') ? $request->get('use_all_locations') : 'true';
        $centerTitle = $request->has('center_title') ? $request->get('center_title') : '';
        $centerSubTitle = $request->has('center_sub_title') ? $request->get('center_sub_title') : '';
        $centerUrl = $request->has('center_url') ? $request->get('center_url') : '';
        $centerAppBrandUserName = $request->has('center_app_brand_user_name') ? $request->get('center_app_brand_user_name') : '';
        $centerAppBrandPass = $request->has('center_app_brand_pass') ? $request->get('center_app_brand_pass') : '';
        $customUrlName = $request->has('custom_url_name') ? $request->get('custom_url_name') : '';
        $customUrl = $request->has('custom_url') ? $request->get('custom_url') : '';
        $customUrlSubTitle = $request->has('custom_url_sub_title') ? $request->get('custom_url_sub_title') : '';
        $customAppBrandUserName = $request->has('custom _app_brand_user_name') ? $request->get('custom _app_brand_user_name') : '';
        $customAppBrandPass = $request->has('custom _app_brand_pass') ? $request->get('custom _app_brand_pass') : '';
        $promotionUrlName = $request->has('promotion_url_name') ? $request->get('promotion_url_name') : '';
        $promotionUrl= $request->has('promotion_url') ? $request->get('promotion_url') : '';
        $promotionUrlSubTitle  = $request->has('promotion_url_sub_title') ? $request->get('promotion_url_sub_title') : '';
        $promotionAppBrandUserName = $request->has('promotion _app_brand_user_name') ? $request->get('promotion _app_brand_user_name') : '';
        $promotionAppBrandPass = $request->has('promotion _app_brand_pass') ? $request->get('promotion _app_brand_pass') : '';
        $getLimit = $request->has('get_limit') ? $request->get('get_limit') : '';
        $useLimit = $request->has('use_limit') ? $request->get('use_limit') : '';
        $canShare = $request->has('can_share') ? $request->get('can_share') : '';
        $canGiveFriend = $request->has('can_give_friend') ? $request->get('can_give_friend') : '';




        //不同优惠券对应的额外字段

        //团购券
        $deal_detail = $request->has('deal_detail') ? $request->get('deal_detail') : '';

        //代金券
        $leastCost = $request->has('least_cost') ? $request->get('least_cost') : ''; //代金券专用，表示起用金额（单位为分）,如果无起用门槛则填0。
        $reduceCost = $request->has('reduce_cost') ? $request->get('reduce_cost') : '';//代金券专用，表示减免金额。（单位为分）

        //折扣券,折扣券专用，表示打折额度（百分比）。填30就是七折
        $discount = $request->has('discount') ? $request->get('discount') : '';

        //兑换券,兑换券专用，填写兑换内容的名称。
        $gift = $request->has('gift') ? $request->get('gift') : '';

        //优惠券专用，填写优惠详情。
        $defaultDetail = $request->has('default_detail') ? $request->get('default_detail') : '';


        //Advanced_info（卡券高级信息）字段
//        $advancedInfo = $request->has('advanced_info') ? $request->get('advanced_info') : '';
//        $useCondition = $request->has('use_condition') ? $request->get('use_condition') : '';
        $acceptCategory = $request->has('accept_category') ? $request->get('accept_categoryn') : '';
        $rejectCategory = $request->has('reject_category') ? $request->get('reject_category') : '';



        $attributes = [
            'base_info' => [
                'logo_url' => $logoUrl,
                'brand_name' => $brandName,
                'code_type' => $codeType,
                'title' => $title,
                'color' => $color,
                'notice' => $notice,
                'service_phone' => $servicePhone,
                'description' => $description,
                'date_info' => [
                    'type'=>$type,
                    'begin_timestamp'=>$beginTimestamp,
                    'end_timstamp'=>$endTimstamp,
                    'fixed_term'=>$fixedTerm,
                    'fixed_begin_term'=>$fixedBeginTerm,
                ],
                'sku'=> [
                    'quantity'=>$quantity,
                ],
                'use_limit' => $useLimit,
                'get_limit' => $getLimit,
                'use_custom_code' => $useCustomCode,
                'bind_openid' => $bindOpenid,
                'can_share'=> $canShare,
                'can_give_friend'=> $canGiveFriend,
//                'location_id_list'=> $locationIdList,
                'use_all_locations'=>$useAllLocations,
                'center_title'=> $centerTitle,
                'center_sub_title' => $centerSubTitle,
                'center_url' => $centerUrl,
                'custom_url_name' => $customUrlName,
                'custom_url' => $customUrl,
                'custom_url_sub_title' => $customUrlSubTitle,
                'promotion_url_name' => $promotionUrlName,
                'promotion_url' => $promotionUrl,
                'promotion_url_sub_title' => $promotionUrlSubTitle,
                'promotion_app_brand_user_name' => $promotionAppBrandUserName,
                'promotion_app_brand_pass' => $promotionAppBrandPass,


                //...
            ],
            'advanced_info' => [
                'use_condition' => [
                    'accept_category' => $acceptCategory,
                    'reject_category' => $rejectCategory,
                    'can_use_with_other_discount' => true,
                ],
                'abstract' => [

                ],
                'text_image_list' => [

                ],
                'time_limit' => [

                ],
                'business_service' => [

                ],

            ],
            'deal_detail' => $deal_detail,
            'least_cost' => $leastCost,
            'reduce_cost' => $reduceCost,
            'discount' => $discount,
            'gift' => $gift,
            'default_detail' => $defaultDetail,
            //...
        ];


        $result = $card->update($cardId, $cardType, $attributes);

        return $result;

    }

}