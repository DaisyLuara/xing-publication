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
            'name' => $attribute->name,
            'display_name' => $attribute->display_name,
        ];
    }
}