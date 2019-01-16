<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange;
use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductChuchang;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\WarehouseChangeTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WarehouseChangeController extends Controller
{
    //单个调拨记录列表
    public function show(WarehouseChange $warehousechange)
    {
        return $this->response()->item($warehousechange, new WarehouseChangeTransformer());
    }

    //调拨记录列表,传参为product_id
    public function list(Request $request, WarehouseChange $warehousechange)
    {
        $query = $warehousechange->query();
        //根据sku查询
        if ($request->id) {
            $query->where('product_id', $request->id);
        }

        //根据调出库位查询
        if ($request->out_location) {
            $query->where('out_location', $request->out_location);
        }

        //根据调入库位查询
        if ($request->in_location) {
            $query->where('in_location', $request->in_location);
        }

        $warehousechange = $query->paginate(10);
        return $this->response()->paginator($warehousechange, new WarehouseChangeTransformer());
    }

    //硬件出厂，批量增加调拨记录
    public function chuchang(Request $request, WarehouseChange $warehousechange)
    {
        $param = $request->all();

        if ($request->has('product_content') && $request->has('contract_id')) {
            $contract_id = $param['contract_id'];
            $content = $param['product_content'];
            foreach ($content as $item) {
                //记录库存明细,商场库存增加(默认location_id=2)
                unset($item['out_location_name']);
                unset($item['attribute']);
                unset($item['product_sku']);
                //判断之后直接入库
                LocationProduct::updateOrCreate(['location_id' => $item['out_location'], 'product_id' => $item['product_id']]);
                LocationProduct::updateOrCreate(['location_id' => 2, 'product_id' => $item['product_id']]);
                //出库库位，减少库存
                $query = LocationProduct::query()->where('location_id', $item['out_location'])
                    ->where('product_id', $item['product_id'])
                    ->where('stock', '>=', $item['num']);
                if ($query->exists() == 'true') {
                    $query->decrement('stock', $item['num']);
                } else {
                    //库存不足，返回状态码110，并返回SKU和库位信息
                    $sku = Product::find($item['product_id'])->sku;
                    $location = Location::find($item['out_location'])->name;
                    $data = [
                        'error_code'=>'110',
                        'sku'=> $sku,
                        'location'=>$location
                    ];
                    return $data;
                }
                //硬件出厂，入场库位默认为商场，
                LocationProduct::query()->where('location_id', 2)
                    ->where('product_id', $item['product_id'])
                    ->increment('stock', $item['num']);
                $warehousechange->create(array_merge($item, ['in_location' => 2]));
            }

            //合同状态改为已出厂
            Contract::find($contract_id)->update(['product_status' => 2]);
            //记录出厂详情
            ProductChuchang::Create(['contract_id' => $contract_id, 'product_content' => \GuzzleHttp\json_encode($content)]);
        }

        $hardwarechange = $warehousechange->query()->paginate(10);
        return $this->response()->paginator($hardwarechange, new WarehouseChangeTransformer());
    }

    //新增调拨记录
    public function create(Request $request, WarehouseChange $warehousechange)
    {

        //$request->num 调整数量;$request->in_location 调入库位，增加;$request->out_location 调出库位，减少
        //判断之后直接入库
        LocationProduct::updateOrCreate(['location_id' => $request->out_location, 'product_id' => $request->product_id]);
        LocationProduct::updateOrCreate(['location_id' => $request->in_location, 'product_id' => $request->product_id]);
        //对某件商品的调出库位，减少库存量，并记录,此时要判断库存是否足够，不够则提示库存不足
        $query = LocationProduct::query()->where('location_id', $request->out_location)
            ->where('product_id', $request->product_id)
            ->where('stock', '>=', $request->num);
        if ($query->exists() == 'true') {
            $query->decrement('stock', $request->num);
        } else {
            ////库存不足，返回状态码110，并返回SKU和库位信息
            $sku = Product::find($request->product_id)->sku;
            $location = Location::find($request->out_location)->name;
            $data = [
                'error_code'=>'110',
                'sku'=> $sku,
                'location'=>$location
            ];
            return $data;
        }
        //对某件商品的调入库位，增加库存量，并记录
        LocationProduct::query()->where('location_id', $request->in_location)
            ->where('product_id', $request->product_id)
            ->increment('stock', $request->num);
        //记录库存变化
        $warehousechange->fill($request->all())->saveOrFail();
        return $this->response->item($warehousechange, new WarehouseChangeTransformer());
    }
}