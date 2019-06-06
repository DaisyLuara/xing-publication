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
use Illuminate\Http\Request;

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

        activity('create_erp_attribute_value')
            ->causedBy($this->user())
            ->performedOn($value)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增Erp属性值');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update()
    {

    }

    public function delete(ErpAttributeValue $value, Request $request)
    {
        $value->delete();

        activity('delete_erp_attribute_value')
            ->causedBy($this->user())
            ->performedOn($value)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => []])
            ->log('删除Erp属性值');

        return $this->response()->noContent()->setStatusCode(204);
    }
}