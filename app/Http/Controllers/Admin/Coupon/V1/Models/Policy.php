<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:27
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Models\Model;
use App\Http\Controllers\Admin\Company\V1\Models\Company;

/**
 * Class Policy
 *
 * @package App\Http\Controllers\Admin\Coupon\V1\Models
 * @property int $company_id
 * @property int $id
 * @property int|null $create_user_id
 * @property int $create_customer_id 创建客户id
 * @property int $bd_user_id 关联BD
 * @property string $name 投放策略
 * @property string $desc 描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch[] $batches
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $company
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereBdUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereCreateCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $per_person_unlimit 每人开启无限领取,0:关闭,1:开启,
 * @property int $per_person_times 每人领取数量
 * @property int $per_person_per_day_unlimit 每人每天开启无限领取,0:关闭,1:开启
 * @property int $per_person_per_day_times 每人每天领取数量
 * @property int $type 策略类型:1:抽奖,2:券包
 * @property int|null $default_plid 节目ID
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereDefaultPlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy wherePerPersonPerDayTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy wherePerPersonPerDayUnlimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy wherePerPersonTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy wherePerPersonUnlimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereType($value)
 */
class Policy extends Model
{
    protected $fillable = [
        'name',
        'desc',
        'company_id',
        'create_user_id',
        'type',
        'per_person_unlimit',
        'per_person_times',
        'per_person_per_day_unlimit',
        'per_person_per_day_times',
        'bd_user_id',
    ];

    public function batches()
    {
        return $this->belongsToMany(CouponBatch::class)->withPivot(['rate', 'min_age', 'max_age', 'max_score', 'min_score', 'gender', 'type', 'id']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}