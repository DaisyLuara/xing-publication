<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 上午10:09
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany
 *
 * @property int $id
 * @property int $user_id
 * @property string $name 开票公司名称
 * @property string $taxpayer_num 纳税人识别号
 * @property string|null $phone 手机
 * @property string|null $telephone 固定电话
 * @property string $address 地址
 * @property string $account_bank 开户行
 * @property string $account_number 开户账号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereAccountBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereTaxpayerNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany whereUserId($value)
 * @mixin \Eloquent
 */
class InvoiceCompany extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'taxpayer_num',
        'phone',
        'telephone',
        'address',
        'account_bank',
        'account_number'
    ];
}