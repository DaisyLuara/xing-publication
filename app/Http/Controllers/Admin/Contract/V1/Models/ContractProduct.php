<?php

namespace App\Http\Controllers\Admin\Contract\V1\Models;

use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct whereProductColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractProduct whereProductStock($value)
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

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function invoiceReceipt(): BelongsTo
    {
        return $this->belongsTo(InvoiceReceipt::class, 'invoice_receipt_id', 'id');
    }
}