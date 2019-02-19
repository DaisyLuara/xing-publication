<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:27
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Http\Controllers\Admin\Point\V1\Models\Point;
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
        return $this->belongsToMany(CouponBatch::class)->withPivot(['rate', 'min_age', 'max_age', 'max_score', 'min_score', 'gender', 'type', 'id']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function points()
    {
        return $this->belongsToMany(Point::class, 'policy_launch_points', 'policy_id', 'oid');
    }
}