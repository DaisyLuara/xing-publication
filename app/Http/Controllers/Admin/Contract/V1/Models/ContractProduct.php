<?php

namespace App\Http\Controllers\Admin\Contract\V1\Models;

use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct
 *
 * @property int $id
 * @property int|null $contract_id 合同 ID
 * @property string|null $product_name 硬件型号
 * @property string|null $product_color 硬件颜色
 * @property string|null $product_stock 硬件数量
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract|null $contract
 * @property-read \App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt $invoiceReceipt
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct whereProductColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct whereProductStock($value)
 * @mixin \Eloquent
 */
class ContractProduct extends Model
{
    protected $fillable = [
        'contract_id',
        'product_name',
        'product_color',
        'product_stock'
    ];
    public $timestamps = false;

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function invoiceReceipt()
    {
        return $this->belongsTo(InvoiceReceipt::class, 'invoice_receipt_id', 'id');
    }
}