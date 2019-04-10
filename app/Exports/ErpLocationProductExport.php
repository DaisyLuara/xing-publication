<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;

class ErpLocationProductExport extends BaseExport
{

    private $id;//SKU
    private $location;//仓库
    private $warehouse;//仓库位置

    public function __construct($request)
    {
        $this->id = $request->id;
        $this->location = $request->location;
        $this->warehouse = $request->warehouse;

        $this->fileName = '仓库-库存明细列表';
    }

    public function collection()
    {
        $query = LocationProduct::query();
        //根据产品SKU查询,传入product_id
        if ($this->id) {
            $query->where('product_id', $this->id);
        }

        //根据库位查询
        if ($this->location) {
            $query->where('location_id', $this->location);
        }

        //根据仓库查询
        if ($this->warehouse) {
            $query->whereHas('location', function ($q) {
                $q->where('warehouse_id', $this->warehouse);
            });
        }

        $locationProducts = $query->orderByDesc('id')->get()
            ->map(function ($locationProduct) {

                return [
                    'id' => $locationProduct->id,
                    'sku' => isset($locationProduct->product->sku) ? $locationProduct->product->sku : '',
                    'location' => isset($locationProduct->location->name) ? $locationProduct->location->name : '',
                    'warehouse' => ($locationProduct->location && $locationProduct->location->warehouse) ? $locationProduct->location->warehouse->name : '',
                    'stock' => (int)$locationProduct->stock,
                ];
            })->toArray();

        $header = ['ID', 'SKU', '仓库', ' 库存位置', '库存数量'];

        $this->header_num = count($header);
        array_unshift($locationProducts, $header, $header);
        $this->data = $data = collect($locationProducts);

        return $data;
    }


}