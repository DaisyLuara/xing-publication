<?php

namespace App\Http\Controllers\Admin\Contract\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    public $fillable = [
        'contract_number',
        'name',
        'company_id',
        'applicant',
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
        'start_date',
        'end_date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function handlerUser()
    {
        return $this->belongsTo(User::class, 'handler', 'id');
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id');
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'contract_media', 'contract_id', 'media_id');
    }

    public function receiveDate()
    {
        return $this->hasMany(ContractReceiveDate::class, 'contract_id', 'id');
    }

    //合同硬件，和合同收款日期类似
    public function product()
    {
        return $this->hasMany(ContractProduct::class, 'contract_id', 'id');
    }

    public function contractHistory()
    {
        return $this->hasMany(ContractHistory::class, 'contract_id', 'id');
    }

    public function contractCost()
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

    public static  $typeMapping = [
        '0' => '收款合同',
        '1' => '付款合同',
        '2' => '其它合同',
    ];

    public static  $productStatusMapping = [
        '0' => '无硬件',
        '1' => '未出厂',
        '2' => '已出厂',
    ];

    public static  $kindMapping = [
        '0' => null,
        '1' => '铺屏',
        '2' => '销售',
        '3' => '租赁',
        '4' => '服务'
    ];

    public static  $targetMapping = [
        '1' => '商户',
        '2' => '商场'
    ];

    public static  $chargeMapping = [
        '0' => '否',
        '1' => '是'
    ];
}
