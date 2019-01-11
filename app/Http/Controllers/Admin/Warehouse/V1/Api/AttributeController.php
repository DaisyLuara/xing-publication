<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Attribute;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\AttributeTransformer;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    //产品属性列表
    public function list(Attribute $attribute)
    {
        $query = $attribute->query();
        $product = $query->orderBy('created_at', 'asc')->get();
        return $this->response()->collection($product, new AttributeTransformer())->setStatusCode(200);
    }

}
