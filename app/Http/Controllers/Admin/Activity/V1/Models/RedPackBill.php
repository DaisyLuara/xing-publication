<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019/1/22
 * Time: 下午5:39
 */

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill
 *
 * @property int $id
 * @property int $coupon_batch_id 优惠券规则配置
 * @property string $coupon_code 优惠券code
 * @property string $mch_billno 商户订单号
 * @property string $mch_id 微信支付分配的商户号
 * @property string $wxappid 微信分配的公众账号ID
 * @property string $send_name 红包发送者名称
 * @property string $re_openid 接受红包的用户openid
 * @property int $total_amount 付款金额，单位分
 * @property int $total_num 接受红包的用户openid
 * @property string $scene_id 发放红包使用场景，红包金额大于200或者小于1元时必传
 * @property string $return_code SUCCESS/FAIL
 * @property string $return_msg 返回信息，如非空，为错误原因
 * @property string $result_code 当状态为FAIL时，存在业务结果未明确的情况
 * @property string $err_code 错误码信息
 * @property string $err_code_des 结果信息描述
 * @property string $send_listid 红包订单的微信单号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $remark 备注
 * @property string $wishing 红包祝福语
 * @property string $act_name 活动名称
 * @property-read \App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch $couponBatch
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereActName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereCouponBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereErrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereErrCodeDes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereMchBillno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereMchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereReOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereResultCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereReturnCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereReturnMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereSceneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereSendListid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereSendName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereTotalNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereWishing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill whereWxappid($value)
 * @mixin \Eloquent
 */
class RedPackBill extends Model
{
    protected $table = 'redpack_bill';

    protected $fillable = [
        'coupon_batch_id',
        'coupon_code',
        'mch_billno',
        'mch_id',
        'wxappid',
        'send_name',
        're_openid',
        'total_amount',
        'total_num',
        'scene_id',
        'return_code',
        'return_msg',
        'result_code',
        'err_code',
        'err_code_des',
        'send_listid',
        'remark',
        'act_name',
        'wishing',
    ];

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class, 'coupon_batch_id', 'id');
    }

}