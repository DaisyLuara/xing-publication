<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午10:47
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory whereUserId($value)
 * @mixin \Eloquent
 */
class InvoiceHistory extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id'
    ];
}