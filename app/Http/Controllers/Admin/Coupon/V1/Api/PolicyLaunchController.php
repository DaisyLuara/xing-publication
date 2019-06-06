<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Api;

use App\Http\Controllers\Admin\Coupon\V1\Request\PolicyLaunchRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyLaunchTransformer;
use App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch;
use App\Http\Controllers\Controller;
use DB;

class PolicyLaunchController extends Controller
{

    public function show(PolicyLaunch $policyLaunch)
    {
        return $this->response->item($policyLaunch, new PolicyLaunchTransformer());
    }

    public function index(PolicyLaunchRequest $request, PolicyLaunch $policyLaunch)
    {
        $query = $policyLaunch->query();

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);

        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('oid')) {
            $query->where('oid', $request->oid);
        }

        $policyLaunches = $query->orderByDesc('id')->paginate(10);

        return $this->response->paginator($policyLaunches, new PolicyLaunchTransformer());
    }

    public function store(PolicyLaunchRequest $request, PolicyLaunch $policyLaunch)
    {
        $query = $policyLaunch->query();

        //检查是否已经投放
        $policyLaunchExists = PolicyLaunch::query()->where('oid', $request->get('oid'))
            ->where('project_id', $request->get('project_id'))->first();
        abort_if($policyLaunchExists, 500, '当前点位已被投放！');

        $query->create(array_merge([
            'company_id' => $request->company_id,
            'belong' => $request->versionname,
        ], $request->all()));

        activity('create_policy_launch')
            ->causedBy($this->user())
            ->performedOn($policyLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增奖品投放');

        return $this->response->noContent();
    }

    public function update(PolicyLaunch $policyLaunch, PolicyLaunchRequest $request)
    {
        $policyLaunch->fill(array_merge(['belong' => $request->versionname], $request->all()))->update();

        activity('update_policy_launch')
            ->causedBy($this->user())
            ->performedOn($policyLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑奖品投放');

        return $this->response->noContent();
    }

}