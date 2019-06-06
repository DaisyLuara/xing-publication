<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Admin\Media\V1\Models\Media;

/**
 * App\Http\Controllers\Admin\Invoice\V1\Models\Invoice
 *
 * @property int $id
 * @property int $contract_id 合同id
 * @property int $applicant 申请人
 * @property int $owner 所属人
 * @property int|null $handler 处理人
 * @property int $type 0:专票,1:普票
 * @property int $invoice_company_id 开票公司id
 * @property int $status 1:待审批,2:审批中,3:已审批,4:已开票,5:已认领,6:驳回
 * @property string|null $kind 种类
 * @property string $total 总计
 * @property string $total_text 总计大写
 * @property string|null $remark 备注
 * @property string|null $bd_ma_message bd主管意见
 * @property string|null $legal_ma_message 法务主管意见
 * @property string|null $drawer 开票人
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User $applicantUser
 * @property-read User $ownerUser
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract $contract
 * @property-read \App\Models\User|null $handlerUser
 * @property-read \App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany $invoiceCompany
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent[] $invoiceContent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory[] $invoiceHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Media\V1\Models\Media[] $media
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Query\Builder|Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereBdMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDrawer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereHandler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereLegalMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTotalText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Invoice withoutTrashed()
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'applicant',
        'owner',
        'handler',
        'type',
        'invoice_company_id',
        'status',
        'kind',
        'total',
        'total_text',
        'remark',
        'bd_ma_message',
        'legal_ma_message',
        'drawer'
    ];

    public function invoiceContent(): HasMany
    {
        return $this->hasMany(InvoiceContent::class, 'invoice_id', 'id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function handlerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler', 'id');
    }

    public function applicantUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function ownerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }

    public function invoiceCompany(): BelongsTo
    {
        return $this->belongsTo(InvoiceCompany::class, 'invoice_company_id', 'id');
    }

    public function invoiceHistory(): HasMany
    {
        return $this->hasMany(InvoiceHistory::class, 'invoice_id', 'id');
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'invoice_media', 'invoice_id', 'media_id');
    }

    public static $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '已开票',
        '5' => '已认领',
        '6' => '驳回',
    ];
}
