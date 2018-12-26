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

        return $official_account->card;
    }

    //获取素材实例
    protected function getMaterial($authorizer_id)
    {
        $app = EasyWeChat::openPlatform();
        componentVerify($app);
        $official_account = getOfficialAccount($authorizer_id, $app);

        return $official_account->material;
    }

    //获取用户授权信息
    public function getInfo($authorizer_id)
    {
        $app = EasyWeChat::openPlatform();
        componentVerify($app);

        $brandNameDefault = $openPlatform->getAuthorizerOption($authorizer_id, 'nick_name');
        $logoUrlDefault = $openPlatform->getAuthorizerOption($authorizer_id, 'head_img');

        $info = [
            'logo_url' => $logoUrlDefault,
            'brand_name' => $brandNameDefault,
        ];

        return $info;
    }

    //上传图片
    public function uploadPic(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $material = $this->getMaterial($authorizer_id);
        $path = $request->has('path') ? $request->get('path') : '';

        return $material->uploadImage($path);
    }

    //上传缩略图
    public function uploadThumb(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $material = $this->getMaterial($authorizer_id);
        $path = $request->has('path') ? $request->get('path') : '';

        return  $material->uploadThumb($path);
    }

    //上传图文消息
    public function uploadArticle(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $material = $this->getMaterial($authorizer_id);
        $title = $request->has('title') ? $request->get('title') : '';
        $mediaId = $request->has('media_id') ? $request->get('media_id') : '';

        $article = new EasyWeChat\Kernel\Messages\Article([
            'title' => $title,
            'thumb_media_id' => $mediaId,
        ]);

        return $material->uploadArticle($article);
    }

    //查询单个卡券列表
    public function show(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $cardId = $request->has('card_id') ? $request->get('card_id') : '';

        return $card->get($cardId);
    }

    //批量查询卡券列表
    public function index(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $statusList = $request->has('status_list') ? $request->get('status_list') : '';

        return $card->list($offset = 0, $count = 50, $statusList);
    }


    //删除卡券
    public function destroy(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $cardId = $request->has('card_id') ? $request->get('card_id') : '';

        return $card->delete($cardId);
    }

    //修改库存
    public function stock(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);
        $cardId = $request->has('card_id') ? $request->get('card_id') : '';

        $increaseStockValue = $request->has('increase_stock_value') ? $request->get('increase_stock_value') : '0';
        $reduceStockValue = $request->has('reduce_stock_value') ? $request->get('reduce_stock_value') : '0';

        if ($increaseStockValue >= 1) {
            return $card->increaseStock($cardId, $increaseStockValue);
        }

        if ($reduceStockValue >= 1) {
            return $card->reduceStock($cardId, $reduceStockValue);
        }

    }

    //创建卡券
    public function store(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);

        $cardArr = $request->json()->all();
        $cardType = array_key_exists('card_type', $cardArr) ? $cardArr['card_type'] : '';
        $attributes = array_key_exists(strtolower($cardType), $cardArr) ? $cardArr[strtolower($cardType)] : '';

        return $card->create($cardType, $attributes);
    }

    //更改卡券信息
    public function update(Request $request)
    {
        $authorizer_id = $request->has('authorizer_id') ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);

        $cardId = $request->has('card_id') ? $request->get('card_id') : '';
        $cardArr = $request->json()->all();
        $cardType = array_key_exists('card_type', $cardArr) ? $cardArr['card_type'] : '';
        $attributes = array_key_exists(strtolower($cardType), $cardArr) ? $cardArr[strtolower($cardType)] : '';

        return $card->update($cardId, $cardType, $attributes);
    }

}