<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Transformer;

use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use League\Fractal\TransformerAbstract;

class PolicyTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['batches', 'company'];

    public function transform(Policy $policy)
    {
        return [
            'id' => $policy->id,
            'name' => $policy->name,
            'created_at' => $policy->created_at->toDateTimeString(),
            'updated_at' => $policy->updated_at->toDateTimeString(),
        ];
    }

    public function includeBatches(Policy $policy)
    {
        return $this->collection($policy->batches, new CouponBatchTransformer());
    }
}