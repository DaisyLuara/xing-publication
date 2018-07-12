<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyTransformer;
use App\Http\Controllers\Controller;
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

        if (!$loginUser->isAdmin()) {
            $query->where('bd_user_id', '=', $loginUser->id);
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $policy = $query->paginate(10);
        return $this->response->paginator($policy, new PolicyTransformer());
    }

    public function store(Company $company, Policy $policy, PolicyRequest $request)
    {
        $policy->fill(array_merge([
            'company_id' => $company->id,
            'create_user_id' => $this->user->id,
            'bd_user_id' => $company->user_id,
        ], $request->all()))->save();
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function update(Policy $policy, PolicyRequest $request)
    {
        $policy->update($request->all());
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function storeBatchPolicy(Policy $policy, CouponBatch $couponBatch, PolicyBatchesRequest $request)
    {
        $couponBatch = $couponBatch->query()->findOrFail($request->coupon_batch_id);

        $policy->batches()->save($couponBatch, $this->convert($request));
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function updateBatchPolicy(Policy $policy, $batch_policy_id, PolicyBatchesRequest $request)
    {
        $query = DB::table('coupon_batch_policy');
        $query->where('id', '=', $batch_policy_id)->update($this->convert($request));
        return $this->response->item($policy, new PolicyTransformer())
            ->setStatusCode(201);
    }

    public function destroyBatchPolicy(Policy $policy, $batch_policy_id)
    {
        $query = DB::table('coupon_batch_policy');
        $query->delete($batch_policy_id);
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