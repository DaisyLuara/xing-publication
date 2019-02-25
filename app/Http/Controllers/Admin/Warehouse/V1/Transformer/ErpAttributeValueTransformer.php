<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/2/18
 * Time: 上午11:20
 */

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;


use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue;
use League\Fractal\TransformerAbstract;

class ErpAttributeValueTransformer extends TransformerAbstract
{
    public function transform(ErpAttributeValue $value)
    {
        return [
            'id' => $value->id,
            'attribute_id' => $value->attribute_id,
            'value' => $value->value
        ];
    }
}