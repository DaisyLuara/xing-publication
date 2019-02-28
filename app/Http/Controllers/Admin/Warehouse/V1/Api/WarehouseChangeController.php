<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory;
use App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange;
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
    public function index(Request $request, WarehouseChange $warehousechange)
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
    public function factory(Request $request, WarehouseChange $warehousechange)
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
                //检查记录
                $this->checkRecord(2, $item['out_location'], $item['product_id']);
                //出库
                if ($item['out_location'] == 1) {
                    $this->supplierLocationStock($item['product_id'], $item['num']);
                } else {
                    $this->checkoutLocationStock($item['out_location'], $item['product_id'], $item['num']);
                }
                //硬件出厂，入场库位默认为商场，
                $this->inLocation(2, $item['product_id'], $item['num']);
                $warehousechange->create(array_merge($item, ['in_location' => 2]));
            }
            //合同状态改为已出厂
            Contract::find($contract_id)->update(['product_status' => 2]);
            //记录出厂详情
            ProductFactory::Create(['contract_id' => $contract_id, 'product_content' => \GuzzleHttp\json_encode($content)]);
        }

        $hardwarechange = $warehousechange->query()->paginate(10);
        return $this->response()->paginator($hardwarechange, new WarehouseChangeTransformer());
    }

    //新增调拨记录
    public function store(Request $request, WarehouseChange $warehouseChange)
    {

        //$request->num 调整数量;$request->in_location 调入库位，增加;$request->out_location 调出库位，减少

        $inLocation = $request->has('in_location') ? $request->get('in_location') : '';
        $outLocation = $request->has('out_location') ? $request->get('out_location') : '';
        $productId = $request->has('product_id') ? $request->get('product_id') : '';
        $num = $request->has('num') ? $request->get('num') : '';

        //检查记录
        $this->checkRecord($inLocation, $outLocation, $productId);
        //出库
        if ($outLocation == 1) {
            $this->supplierLocationStock($productId, $num);
        } else {
            $this->checkoutLocationStock($outLocation, $productId, $num);
        }
        //入库
        $this->inLocation($inLocation, $productId, $num);
        //记录库存变化
        $warehouseChange->fill($request->all())->saveOrFail();
        return $this->response->item($warehouseChange, new WarehouseChangeTransformer());
    }

    //出库，库存不足报500
    private function checkoutLocationStock($outLocation, $productId, $num)
    {
        $LocationProductModel = LocationProduct::query()->where('location_id', $outLocation)
            ->where('product_id', $productId)
            ->where('stock', '>=', $num)->first();
        abort_if((!$LocationProductModel), 500, '出库库位库存不足');
        $LocationProductModel->decrement('stock', $num);
    }

    //出库库位为供应商,locationId为1
    private function supplierLocationStock($productId, $num)
    {
        LocationProduct::query()->where('location_id', 1)
            ->where('product_id', $productId)
            ->decrement('stock', $num);
    }

    //入库
    private function inLocation($inLocation, $productId, $num)
    {
        LocationProduct::query()->where('location_id', $inLocation)
            ->where('product_id', $productId)
            ->increment('stock', $num);
    }

    //判断是否存在初始记录，不存在则初始化库存
    private function checkRecord($inLocation, $outLocation, $productId)
    {
        LocationProduct::updateOrCreate(['location_id' => $inLocation, 'product_id' => $productId]);
        LocationProduct::updateOrCreate(['location_id' => $outLocation, 'product_id' => $productId]);
    }
}