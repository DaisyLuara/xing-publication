<?php
namespace App\Http\Controllers\Admin\Cards\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EasyWeChat;

class CardsController extends Controller
{

    //获取实例
    protected function getCard($authorizer_id)
    {
        $app = EasyWeChat::openPlatform();
        componentVerify($app);
        $official_account = getOfficialAccount($authorizer_id, $app);
        $card = $official_account->card;

        return $card;

    }

    //上传图片
    public function uploadpic(Request $request)
    {

        $authorizer_id = $request->has('authorizer_id')  ? $request->get('authorizer_id') : '';
        $card = $this->getCard($authorizer_id);

        $result = $card->material->uploadImage();

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
    public function delete(Request $request)
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
            $card->increaseStock($cardId, $increaseStockValue);
        }

        if($reduceStockValue >= 1 ) {
            $card->reduceStock($card, $reduceStockValue);
        }

    }

}