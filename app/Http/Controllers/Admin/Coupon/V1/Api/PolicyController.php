<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Coupon\V1\Request\PolicyRequest;
use App\Http\Controllers\Admin\Coupon\V1\Request\PolicyBatchesRequest;

class PolicyController extends Controller
{
    public function index(Policy $couponPolicy)
    {
        $query = $couponPolicy->query();
        $couponPolicy = $query->paginate(10);
        return $this->response->paginator($couponPolicy, new PolicyTransformer());
    }

    public function store(Policy $policy, PolicyRequest $request)
    {
        $policy->fill($request->all())->save();
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function update(Policy $policy, Request $request)
    {
        $policy = $policy->update($request->all());
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function storeBatchPolicy(Policy $policy, CouponBatch $couponBatch, PolicyBatchesRequest $request)
    {
        $policy->batches()->save($couponBatch, $this->convert($request));
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function updateBatchPolicy(Policy $policy, $batch_policy_id, PolicyBatchesRequest $request)
    {
        $policy->batches()->updateExistingPivot($batch_policy_id, $this->convert($request));
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    private function convert(Request $request)
    {
        if ($request->min_age && $request->max_age) {
            $type = 'age';
        } else if ($request->rate > 0) {
            $type = 'rate';
        } else if ($request->gender) {
            $type = 'gender';
        }

        return array_merge(['type' => $type], $request->all());

    }
}