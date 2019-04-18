<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 下午2:27
 */

namespace App\Http\Controllers\Admin\Payment\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee
 *
 * @property int $id
 * @property int $user_id
 * @property string $name 收款人姓名
 * @property string $account_bank 收款人开户行
 * @property string $account_number 收款人开户账号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee whereAccountBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee whereUserId($value)
 * @mixin \Eloquent
 */
class PaymentPayee extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'account_bank',
        'account_number',
    ];
}