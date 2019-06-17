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
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereAccountBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereTaxpayerNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceCompany whereUserId($value)
 * @mixin \Eloquent
 */
class InvoiceCompany extends Model
{
    protected $fillable = [
        'user_id',
        'owner',
        'name',
        'taxpayer_num',
        'phone',
        'telephone',
        'address',
        'account_bank',
        'account_number'
    ];
}