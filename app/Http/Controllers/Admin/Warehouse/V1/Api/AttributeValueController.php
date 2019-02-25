<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/2/18
 * Time: 上午11:13
 */

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;


use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue;
use App\Http\Controllers\Admin\Warehouse\V1\Request\AttributeValueRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ErpAttributeValueTransformer;
use App\Http\Controllers\Controller;

class AttributeValueController extends Controller
{
    public function show(ErpAttributeValue $value)
    {

    }

    public function index(ErpAttribute $attribute, ErpAttributeValue $value)
    {
        $query = $value->query();
        $value = $query->whereHas('attribute', function ($q) use ($attribute) {
            $q->where('id', $attribute->id);
        })->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($value, new ErpAttributeValueTransformer());
    }

    public function store(AttributeValueRequest $request, ErpAttributeValue $value)
    {
        $value->fill($request->all())->save();
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update()
    {

    }

    public function delete(ErpAttributeValue $value)
    {
        $value->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }
}