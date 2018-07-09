<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;

class CouponBatchTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'company'];

    public function transform(CouponBatch $couponBatch)
    {
        return [
            'id' => $couponBatch->id,
            'name' => $couponBatch->name,
            'image_url' => $couponBatch->image_url,
            'amount' => $couponBatch->amount,
            'count' => $couponBatch->count,
            'stock' => $couponBatch->stock,
            'people_max_get' => $couponBatch->people_max_get,
            'pmg_status' => $couponBatch->pmg_status,
            'day_max_get' => $couponBatch->day_max_get,
            'dmg_status' => $couponBatch->dmg_status,
            'effective_day' => $couponBatch->effective_day,
            'start_date' => $couponBatch->start_date,
            'end_date' => $couponBatch->end_date,
            'is_active' => $couponBatch->is_active,
            'pivot' => $couponBatch->pivot,
        ];
    }

    public function includeUser(CouponBatch $couponBatch)
    {
        return $this->item($couponBatch->user, new UserTransformer());
    }

    public function includeCompany(CouponBatch $couponBatch)
    {
        return $this->item($couponBatch->company, new CompanyTransformer());
    }
}