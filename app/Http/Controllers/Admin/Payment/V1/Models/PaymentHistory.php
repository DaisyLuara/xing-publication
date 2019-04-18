<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 下午3:24
 */

namespace App\Http\Controllers\Admin\Payment\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $payment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory whereUserId($value)
 * @mixin \Eloquent
 */
class PaymentHistory extends Model
{
    protected $fillable = [
        'user_id',
        'payment_id'
    ];
}