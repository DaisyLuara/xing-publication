<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;

class ErpProductExport extends BaseExport
{

    private $id;//SKU
    private $supplier;//供应商

    public function __construct($request)
    {
        $this->id = $request->id;
        $this->supplier = $request->supplier;
        $this->fileName = '仓库-产品管理列表';
    }

    public function collection()
    {

        $query = Product::query();
        //根据产品SKU查询,传入product_id
        if ($this->id) {
            $query->where('id', $this->id);
        }
        //根据供应商查询
        if ($this->supplier) {
            $query->where('supplier', $this->supplier);
        }

        $attribute_names = ErpAttribute::query()->pluck('display_name', 'id')->toArray();
        $products = $query->orderByDesc('created_at')->get()
            ->map(function ($product) use ($attribute_names) {
                $attribute = '';
                $attributes = $product->attributes->pluck("attributes_value", "attributes_id")->toArray();
                foreach ($attributes as $key => $value) {
                    $attribute .= ($attribute_names[$key] ?? $key) . ':' . $value . ";";
                }

                return [
                    'id' => $product->id,
                    'sku' => $product->sku, //硬件型号
                    'attribute' => $attribute,
                    'supplier_name' => $product->company ? $product->company->name : '',//供应商
                    'created_at' => $product->created_at->toDateTimeString(),
                    'updated_at' => $product->updated_at->toDateTimeString(),
                ];
            })->toArray();

        $header = ['ID', 'SKU', '产品特性', '供应商', '创建时间', '最后操作时间'];

        $this->header_num = count($header);
        array_unshift($products, $header, $header);
        $this->data = $data = collect($products);

        return $data;
    }


}