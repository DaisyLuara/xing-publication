<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\AttributeRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ErpAttributeTransformer;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    public function show(ErpAttribute $attribute)
    {
        return $this->response()->item($attribute, new ErpAttributeTransformer());
    }

    public function index(ErpAttribute $attribute)
    {
        $query = $attribute->query();
        $attribute = $query->orderBy('created_at', 'asc')->paginate(10);
        return $this->response()->paginator($attribute, new ErpAttributeTransformer());
    }

    public function store(AttributeRequest $request, ErpAttribute $attribute)
    {
        $attribute->fill($request->all())->save();
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(AttributeRequest $request, ErpAttribute $attribute)
    {
        $attribute->update($request->all());
        return $this->response()->noContent();
    }

    public function delete(ErpAttribute $attribute)
    {
        $attribute->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }
}
