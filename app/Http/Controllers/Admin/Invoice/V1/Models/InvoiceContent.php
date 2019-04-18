<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/16
 * Time: 下午1:34
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $invoice_kind_id
 * @property int $goods_service_id
 * @property int $num 数量
 * @property string $price 单价
 * @property string $money 金额
 * @property-read \App\Http\Controllers\Admin\Invoice\V1\Models\GoodsService $goodsService
 * @property-read \App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind $invoiceKind
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent whereGoodsServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent whereInvoiceKindId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent wherePrice($value)
 * @mixin \Eloquent
 */
class InvoiceContent extends Model
{
    protected $fillable = [
        'invoice_id',
        'invoice_kind_id',
        'goods_service_id',
        'num',
        'price',
        'money'
    ];
    public $timestamps = false;

    public function invoiceKind()
    {
        return $this->belongsTo(InvoiceKind::class, 'invoice_kind_id', 'id');
    }

    public function goodsService()
    {
        return $this->belongsTo(GoodsService::class, 'goods_service_id', 'id');
    }
}