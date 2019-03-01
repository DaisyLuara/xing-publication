<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute;
use League\Fractal\TransformerAbstract;

class ErpAttributeTransformer extends TransformerAbstract
{

    public function transform(ErpAttribute $attribute)
    {
        return [
            'id' => $attribute->id,
            'name' => $attribute->name,
            'display_name' => $attribute->display_name,
            'options' => $attribute->attributeValues()->selectRaw("id,value")->get()->toArray()
        ];
    }


}