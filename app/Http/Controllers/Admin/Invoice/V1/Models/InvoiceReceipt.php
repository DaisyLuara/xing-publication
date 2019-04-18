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
 * @package App\Http\Controllers\Admin\Invoice\V1\Models
 * @property int id
 * @property  float receipt_money
 * @property int claim_status
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