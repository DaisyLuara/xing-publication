<?php

namespace App\Http\Controllers\Admin\Contract\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Controllers\Admin\Contract\V1\Models\Contract
 *
 * @property int $id
 * @property string|null $contract_number 合同编号
 * @property string $name
 * @property int $company_id
 * @property int $applicant 申请人
 * @property int $owner 所属人
 * @property int $status 1:待审批,2:审批中,3:已审批,4:特批,5:驳回
 * @property int|null $handler 处理人
 * @property int $type 0:收款合同,1:付款合同
 * @property int $product_status 0:无硬件，1:未出厂，2:已出厂
 * @property int $kind 合同种类 1:铺屏,2:销售,3:租赁,4:服务
 * @property int|null $serve_target 服务对象 1:商户,2:商场
 * @property int|null $recharge 预充值 0:否,1:是
 * @property int $special_num 定制节目数量
 * @property int $common_num 通用节目数量
 * @property string|null $amount
 * @property string|null $remark
 * @property string|null $legal_message 法务意见
 * @property string|null $legal_ma_message 法务主管意见
 * @property string|null $bd_ma_message bd主管意见
 * @property string|null $filed_date 归档日期
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property float|null $pbi_money pbi奖金总数，即：合同收款金额-费用
 * @property string|null $pbi_date pbi奖金发放时间
 * @property-read \App\Models\User $applicantUser
 * @property-read User $ownerUser
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $company
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\ContractCost $contractCost
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Contract\V1\Models\ContractHistory[] $contractHistory
 * @property-read \App\Models\User $createUser
 * @property-read \App\Models\User|null $handlerUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Media\V1\Models\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct[] $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate[] $receiveDate
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newQuery()
 * @method static \Illuminate\Database\Query\Builder|Contract onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereBdMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCommonNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereContractNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereHandler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereLegalMaMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereLegalMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract wherePbiDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract wherePbiMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereProductStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereRecharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereServeTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereSpecialNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Contract withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Contract withoutTrashed()
 * @mixin \Eloquent
 */
class Contract extends Model
{
    use SoftDeletes;

    public $fillable = [
        'contract_number',
        'name',
        'company_id',
        'applicant',
        'owner',
        'handler',
        'status',
        'processing_person',
        'type',
        'product_status',
        'kind',
        'serve_target',
        'recharge',
        'special_num',
        'common_num',
        'amount',
        'remark',
        'legal_message',
        'legal_ma_message',
        'bd_ma_message',
        'filed_date'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'contract_media', 'contract_id', 'media_id');
    }

    public function receiveDate(): HasMany
    {
        return $this->hasMany(ContractReceiveDate::class, 'contract_id', 'id');
    }

    //合同硬件，和合同收款日期类似
    public function product(): HasMany
    {
        return $this->hasMany(ContractProduct::class, 'contract_id', 'id');
    }

    public function contractHistory(): HasMany
    {
        return $this->hasMany(ContractHistory::class, 'contract_id', 'id');
    }

    public function contractCost(): HasOne
    {
        return $this->hasOne(ContractCost::class, 'contract_id', 'id');
    }


    public static $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '特批',
        '5' => '驳回'
    ];

    public static $typeMapping = [
        '0' => '收款合同',
        '1' => '付款合同',
        '2' => '其它合同',
    ];

    public static $productStatusMapping = [
        '0' => '无硬件',
        '1' => '未出厂',
        '2' => '已出厂',
    ];

    public static $kindMapping = [
        '0' => '',
        '1' => '铺屏',
        '2' => '销售',
        '3' => '租赁',
        '4' => '服务'
    ];

    public static $targetMapping = [
        '1' => '商户',
        '2' => '商场'
    ];

    public static $chargeMapping = [
        '0' => '否',
        '1' => '是'
    ];
}
