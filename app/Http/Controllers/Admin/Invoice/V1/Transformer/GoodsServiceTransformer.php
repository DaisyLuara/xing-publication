<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/23
 * Time: 下午6:34
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;


use App\Http\Controllers\Admin\Invoice\V1\Models\GoodsService;
use League\Fractal\TransformerAbstract;

class GoodsServiceTransformer extends TransformerAbstract
{
    public function transform(GoodsService $goodsService)
    {
        return [
            'name' => $goodsService->name,
            'spec_type' => $goodsService->spec_type,
            'unit' => $goodsService->unit
        ];
    }
}