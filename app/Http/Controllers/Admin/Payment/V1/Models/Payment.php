<?php

namespace App\Http\Controllers\Admin\Payment\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Admin\Media\V1\Models\Media;

/**
 * App\Http\Controllers\Admin\Payment\V1\Models\Payment
 *
 * @property int $id
 * @property int $contract_id 合同id
 * @property int $applicant 申请人
 * @property int $owner
 * @property string $amount 申请金额
 * @property int $status 1:待审批,2:审批中,3:已审批,4:已付款,5:驳回
 * @property int|null $handler 处理人
 * @property int $receive_status 0:未收票,1:已收票
 * @property int $type 1:支票,2:电汇单,3:贷记凭证
 * @property string $reason 申请事由
 * @property int $payment_payee_id 收款人id
 * @property string|null $remark 备注
 * @property string|null $bd_ma_message bd主管意见
 * @property string|null $legal_message 法务意见
 * @property string|null $legal_ma_message 法务主管意见
 * @property string|null $auditor_message 审计意见
 * @property string|null $payer 付款人
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User $applicantUser
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract $contract
 * @property-read \App\Models\User|null $handlerUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Media\V1\Models\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Payment\V1\Models\PaymentHistory[] $paymentHistory
 * @property-read \App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee $paymentPayee
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAuditorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereBdMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereHandler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereLegalMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereLegalMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentPayeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereReceiveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Payment withoutTrashed()
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'applicant',
        'owner',
        'handler',
        'amount',
        'type',
        'reason',
        'payment_payee_id',
        'remark',
        'bd_ma_message',
        'legal_message',
        'legal_ma_message',
        'auditor_message',
        'payer',
        'status',
        'receive_status',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function applicantUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function ownerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }

    public function handlerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler', 'id');
    }

    public function paymentPayee(): BelongsTo
    {
        return $this->belongsTo(PaymentPayee::class, 'payment_payee_id', 'id');
    }

    public function paymentHistory(): HasMany
    {
        return $this->hasMany(PaymentHistory::class, 'payment_id', 'id');
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'payment_media', 'payment_id', 'media_id');
    }

    public static $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '已付款',
        '5' => '驳回'
    ];

    public static $typeMapping = [
        '1' => '支票',
        '2' => '电汇单',
        '3' => '贷记凭证'
    ];
}
