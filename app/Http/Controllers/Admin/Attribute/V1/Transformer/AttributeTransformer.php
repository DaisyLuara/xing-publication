<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Transformer;

use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use League\Fractal\TransformerAbstract;


class AttributeTransformer extends TransformerAbstract
{

    public function transform(Attribute $attribute)
    {
        return [
            'id' => $attribute->id,
            'name' => $attribute->name,
        ];
    }

}