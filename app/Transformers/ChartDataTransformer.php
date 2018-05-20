<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/17
 * Time: 20:26
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class ChartDataTransformer extends TransformerAbstract
{
    public function transform($chartData)
    {
        return [
            'display_name' => $chartData->display_name,
            'count' => (int)$chartData->count,
            'index' => (string)$chartData->index
        ];
    }

}