<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory
 *
 * @property int $id
 * @property int|null $contract_id 合同ID
 * @property string|null $product_content 硬件出厂详情
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory whereProductContent($value)
 * @mixin \Eloquent
 */
class ProductFactory extends Model
{
    protected $table = 'erp_product_chuchangs';
    protected $fillable = [
        'contract_id',
        'product_content',
    ];

    public $timestamps = false;
}