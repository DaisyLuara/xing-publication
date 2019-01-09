<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;


use App\Http\Controllers\Admin\Warehouse\V1\Models\Attribute;
use League\Fractal\TransformerAbstract;


class AttributeTransformer extends TransformerAbstract
{
    public function transform(Attribute $attribute)
    {
        return [
            'id' => $attribute->id,
            'name' => $attribute->name, //硬件型号
            'display_name' => $attribute->display_name,
            'value' => $attribute->attributeValues,
        ];
    }
}