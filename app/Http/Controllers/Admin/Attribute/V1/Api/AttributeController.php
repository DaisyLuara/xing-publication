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

        activity('create_attribute')
        ->causedBy($this->user())
        ->performedOn($attribute)
        ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
        ->log('新增属性');

        return $this->response->noContent();
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $data = $request->all();
        $attribute->query()->where('id', '=', $data['id'])->update($data);

        activity('update_attribute')
            ->causedBy($this->user())
            ->performedOn($attribute)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $data])
            ->log('编辑属性');

        return $this->response->noContent();
    }
}
