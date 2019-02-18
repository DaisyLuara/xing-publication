<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Attribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\AttributeRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\AttributeTransformer;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    public function show(Attribute $attribute)
    {
        return $this->response()->item($attribute, new AttributeTransformer());
    }

    public function index(Attribute $attribute)
    {
        $query = $attribute->query();
        $attribute = $query->orderBy('created_at', 'asc')->paginate(10);
        return $this->response()->paginator($attribute, new AttributeTransformer());
    }

    public function store(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->fill($request->all())->save();
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->all());
        return $this->response()->noContent();
    }

    public function delete(Attribute $attribute)
    {
        $attribute->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }
}
