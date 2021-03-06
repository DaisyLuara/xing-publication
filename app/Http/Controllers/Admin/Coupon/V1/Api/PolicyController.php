<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;

use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Coupon\V1\Request\PolicyRequest;
use App\Http\Controllers\Admin\Coupon\V1\Request\PolicyBatchesRequest;
use DB;

class PolicyController extends Controller
{

    public function show(Policy $policy)
    {
        return $this->response->item($policy, new PolicyTransformer());
    }

    public function index(Policy $policy, Request $request)
    {
        $query = $policy->query();

        $loginUser = $this->user;

        if ($loginUser->hasRole('user')) {
            $query->where('bd_user_id', '=', $loginUser->id);
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $policy = $query->orderByDesc('id')->paginate(10);

        return $this->response->paginator($policy, new PolicyTransformer());
    }


    /**
     * @param Company $company
     * @param Policy $policy
     * @return \Dingo\Api\Http\Response
     */
    public function allByCompanyId(Company $company,Policy $policy): Response
    {
        $query = $policy->query()->where('company_id', '=', $company->id);

        $loginUser = $this->user;

        if ($loginUser->hasRole('user')) {
            $query->where('bd_user_id', '=', $loginUser->id);
        }

        $policies = $query->orderByDesc('id')->get();

        return $this->response->collection($policies, new PolicyTransformer());
    }

    public function store(Company $company, Policy $policy, PolicyRequest $request)
    {
        $policy->fill(array_merge([
            'company_id' => $company->id,
            'create_user_id' => $this->user->id,
            'bd_user_id' => $company->user_id,
        ], $request->all()))->save();


        activity('create_policy')
            ->causedBy($this->user())
            ->performedOn($policy)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增奖品模版');

        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function update(Policy $policy, PolicyRequest $request)
    {
        $policy->update($request->except(['company_id']));

        activity('update_policy')
            ->causedBy($this->user())
            ->performedOn($policy)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑奖品模版');

        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function showBatchPolicy(Policy $policy, $batch_policy_id)
    {
        $batchPolicy = $policy->batches()->find($batch_policy_id);
        return $this->response->item($batchPolicy, new CouponBatchTransformer());
    }

    public function batchPolicyIndex(Policy $policy)
    {
        $batchPolicies = $policy->batches()->paginate(10);

        return $this->response->paginator($batchPolicies, new CouponBatchTransformer());
    }

    public function storeBatchPolicy(Policy $policy, CouponBatch $couponBatch, PolicyBatchesRequest $request)
    {
        $couponBatch = $couponBatch->query()->findOrFail($request->coupon_batch_id);

        abort_if($policy->batches()->find($couponBatch->id), 500, '已存在该奖品,请勿重复添加');

        $policy->batches()->save($couponBatch, $this->convert($request));

        activity('create_batch_policy')
            ->causedBy($this->user())
            ->performedOn($policy)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增奖品模版子策略');

        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function updateBatchPolicy(Policy $policy, $batch_policy_id, PolicyBatchesRequest $request)
    {
        $policy->batches()->updateExistingPivot($batch_policy_id, $this->convert($request));

        activity('update_batch_policy')
            ->causedBy($this->user())
            ->performedOn($policy)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑奖品模版子策略');

        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function destroyBatchPolicy(Policy $policy, $batch_policy_id, Request $request)
    {
        $policy->batches()->detach($batch_policy_id);

        activity('delete_batch_policy')
            ->causedBy($this->user())
            ->performedOn($policy)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('删除奖品模版子策略');

        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    private function convert(Request $request)
    {
        if ($request->min_age && $request->max_age) {
            $type = 'age';
        } else if ($request->rate > 0) {
            $type = 'rate';
        } else if ($request->has('gender')) {
            $type = 'gender';
        }

        return array_merge(['type' => $type], $request->all());

    }
}