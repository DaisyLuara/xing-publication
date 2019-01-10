<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

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
        return $this->response()->item($warehousechange, new WarehouseChangeTransformer())->setStatusCode(200);
    }

    //调拨记录列表
    public function list(Request $request, WarehouseChange $warehousechange)
    {
        $query = $warehousechange->query();
        //根据sku查询
        if ($request->sku) {
            $query->where('sku', $request->sku);
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
                //记录库存明细
                $warehousechange->fill(array_merge($item, ['out_location' => $request->out_location, 'in_location' => $request->in_location]))->saveOrFail();
                //判断之后直接入库
                LocationProduct::firstOrCreate(['location_id' => $request->out_location], ['product_sku' => $request->sku]);
                LocationProduct::firstOrCreate(['location_id' => $request->in_location], ['product_sku' => $request->sku]);
                //出库库位，减少库存
                LocationProduct::query()->where([['location_id', '=', $request->out_location], ['product_sku', '=', $item['sku']]])->decrement('stock', $item['num']);
                //硬件出厂，入场库位为商场，商场库存就增加了
                LocationProduct::query()->where([['location_id', '=', $request->in_location], ['product_sku', '=', $item['sku']]])->increment('stock', $item['num']);
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
        //记录库存变化
        $warehousechange->fill($request->all())->saveOrFail();
        //$request->num 调整数量;$request->in_location 调入库位，增加;$request->out_location 调出库位，减少
        //判断之后直接入库
        LocationProduct::firstOrCreate(['location_id' => $request->out_location], ['product_sku' => $request->sku]);
        LocationProduct::firstOrCreate(['location_id' => $request->in_location], ['product_sku' => $request->sku]);
        //对某件商品的调出库位，减少库存量，并记录
        LocationProduct::query()->where([['location_id', '=', $request->out_location], ['product_sku', '=', $request->sku]])->decrement('stock', $request->num);
        //对某件商品的调入库位，增加库存量，并记录
        LocationProduct::query()->where([['location_id', '=', $request->in_location], ['product_sku', '=', $request->sku]])->increment('stock', $request->num);

        return $this->response->item($warehousechange, new WarehouseChangeTransformer());
    }
}