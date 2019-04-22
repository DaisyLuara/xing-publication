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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereBdUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereCreateCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\Policy whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Policy extends Model
{
    protected $fillable = [
        'name',
        'desc',
        'company_id',
        'create_user_id',
        'bd_user_id',
    ];

    protected $casts = [
        'per_person_unlimit' => 'boolean',
        'per_person_per_day_unlimit' => 'boolean',
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