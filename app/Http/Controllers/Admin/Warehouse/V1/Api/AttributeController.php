<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\AttributeRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ErpAttributeTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        activity('create_erp_attribute')
            ->causedBy($this->user())
            ->performedOn($attribute)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all() ])
            ->log('新增Erp属性');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(AttributeRequest $request, ErpAttribute $attribute)
    {
        $attribute->update($request->all());

        activity('update_erp_attribute')
            ->causedBy($this->user())
            ->performedOn($attribute)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all() ])
            ->log('编辑Erp属性');

        return $this->response()->noContent();
    }

    public function delete(ErpAttribute $attribute, Request $request)
    {
        $attribute->delete();

        activity('delete_erp_attribute')
            ->causedBy($this->user())
            ->performedOn($attribute)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => [] ])
            ->log('删除Erp属性');

        return $this->response()->noContent()->setStatusCode(204);
    }
}
