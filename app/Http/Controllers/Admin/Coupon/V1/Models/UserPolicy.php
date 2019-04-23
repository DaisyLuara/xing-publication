<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy
 *
 * @property int $id
 * @property int $wx_user_id
 * @property int $qiniu_id 七牛ID
 * @property int $policy_id 策略礼包ID
 * @property string $belong 节目版本名称
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy wherePolicyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy whereQiniuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy whereWxUserId($value)
 * @mixin \Eloquent
 */
class UserPolicy extends Model
{
    protected $fillable = [
        'wx_user_id',
        'qiniu_id',
        'policy_id',
        'belong',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class, 'policy_id', 'id');
    }

}