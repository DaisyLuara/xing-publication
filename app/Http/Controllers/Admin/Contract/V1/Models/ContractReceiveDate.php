<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/24
 * Time: 上午11:44
 */

namespace App\Http\Controllers\Admin\Contract\V1\Models;


use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ContractReceiveDate
 *
 * @package App\Http\Controllers\Admin\Contract\V1\Models
 * @property int $id
 * @property int $contract_id
 * @property string $receive_date 收款日期
 * @property int $receive_status 0：未收款，1：已收款
 * @property int|null $invoice_receipt_id
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract $contract
 * @property-read \App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt|null $invoiceReceipt
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate whereInvoiceReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate whereReceiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractReceiveDate whereReceiveStatus($value)
 * @mixin \Eloquent
 */
class ContractReceiveDate extends Model
{
    protected $fillable = [
        'contract_id',
        'receive_date',
        'receive_status',
        'invoice_receipt_id'
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