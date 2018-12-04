<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromotionAPIController extends Controller
{

    /**
     * 获取该商场下优惠分类列表
     */
    public function getCategoryList(Request $request){
        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Event/Promotion/V1/GetCategoryList/';
        return response($mall_coo->send($sUrl,$request->all()));
    }

    public function getList(Request $request){
        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Event/Promotion/V1/GetList/';
        return response($mall_coo->send($sUrl,$request->all()));
    }

}
