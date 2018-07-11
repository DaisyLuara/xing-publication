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

class Policy extends Model
{
    protected $fillable = [
        'name',
        'desc',
        'company_id',
        'create_user_id',
        'bd_user_id',
    ];

    public function batches()
    {
        return $this->belongsToMany(CouponBatch::class)->withPivot(['rate', 'min_age', 'max_age', 'gender', 'type', 'id']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}