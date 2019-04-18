<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Admin\Media\V1\Models\Media;

/**
 * App\Http\Controllers\Admin\Invoice\V1\Models\Invoice
 *
 * @property int $id
 * @property int $contract_id 合同id
 * @property int $applicant 申请人
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
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract $contract
 * @property-read \App\Models\User|null $handlerUser
 * @property-read \App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany $invoiceCompany
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent[] $invoiceContent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory[] $invoiceHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Media\V1\Models\Media[] $media
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereBdMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereDrawer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereHandler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereInvoiceCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereLegalMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereTotalText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\Invoice withoutTrashed()
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'applicant',
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

    public function invoiceContent()
    {
        return $this->hasMany(InvoiceContent::class, 'invoice_id', 'id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function handlerUser()
    {
        return $this->belongsTo(User::class, 'handler', 'id');
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function invoiceCompany()
    {
        return $this->belongsTo(InvoiceCompany::class, 'invoice_company_id', 'id');
    }

    public function invoiceHistory()
    {
        return $this->hasMany(InvoiceHistory::class, 'invoice_id', 'id');
    }

    public function media()
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
