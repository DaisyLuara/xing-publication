<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Api;

use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Attribute\V1\Request\AttributeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index(Request $request, Attribute $attribute)
    {
        $query = $attribute->query();
        $attribute = $query->get()->toHierarchy();
        return \Response::json($attribute);
    }

    public function store(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->parent_id = $request->parent_id;
        $attribute->name = $request->name;
        $attribute->desc = $request->desc;
        $attribute->save();
        return $this->response->noContent();
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $data = $request->all();
        $query = $attribute->query();
        $query->where('id', '=', $data['id'])->update($data);
        return $this->response->noContent();
    }
}
