<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/12
 * Time: 下午2:08
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Models\Model;
use App\Models\User;

/**
 * Class InvoiceReceipt
 *
 * @package App\Http\Controllers\Admin\Invoice\V1\Models
 * @property int id
 * @property float receipt_money
 * @property int claim_status
 * @property int $id
 * @property string $receipt_company 付款公司
 * @property string $receipt_money 收款金额
 * @property string $receipt_date 到账日期
 * @property int $claim_status 0:未认领,1:已认领
 * @property string|null $creator 创建人
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate $receiveDate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereClaimStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereReceiptCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereReceiptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereReceiptMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvoiceReceipt extends Model
{
    protected $fillable = [
        'receipt_company',
        'receipt_money',
        'receipt_date',
        'claim_status',
        'creator'
    ];

    public function receiveDate()
    {
        return $this->hasOne(ContractReceiveDate::class, 'invoice_receipt_id', 'id');
    }
}