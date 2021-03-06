<?php

namespace App\Http\Controllers\Admin\ResourceAuth\V1\Api;

use App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth;
use App\Http\Controllers\Admin\ResourceAuth\V1\Request\ProjectAuthRequest;
use App\Http\Controllers\Admin\ResourceAuth\V1\Transformer\ProjectAuthTransformer;
use App\Http\Controllers\Admin\Skin\V1\Models\Skin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Dingo\Api\Http\Response;

class ProjectAuthController extends Controller
{
    /**
     * 节目授权列表
     * @param Request $request
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, ProjectAuth $projectAuth): Response
    {
        $query = $projectAuth->query();


        if ($request->has('project_id') && $request->get('project_id')) {
            $query->where('pid', $request->get('project_id'));
        }

        if ($request->has('customer_id') && $request->get('customer_id')) {
            $customer = Customer::query()->find($request->get('customer_id'));
            $query->where('z', $customer->z);
        }
        $projectAuths = $query->orderByDesc('id')->paginate(10);

        return $this->response->paginator($projectAuths, new ProjectAuthTransformer());
    }

    /**
     * 节目授权详情
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     */
    public function show(ProjectAuth $projectAuth): Response
    {
        return $this->response->item($projectAuth, new ProjectAuthTransformer());
    }

    /**
     * 新建节目授权
     *
     * @param ProjectAuthRequest $projectAuthRequest
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     */
    public function store(ProjectAuthRequest $projectAuthRequest, ProjectAuth $projectAuth): Response
    {
        $insertParams = $this->checkAndGetParams($projectAuthRequest);

        if ($projectAuth->query()->where($insertParams)->first()) {
            abort(422, '该授权对象已经授权过该节目及皮肤');
        }

        $projectAuth->fill($insertParams)->save();

        activity('create_project_auth')
            ->causedBy($this->user())
            ->performedOn($projectAuth)
            ->withProperties(['ip' => $projectAuthRequest->getClientIp(), 'request_params' => $projectAuthRequest->all()])
            ->log('新增节目授权');


        return $this->response->item($projectAuth, new ProjectAuthTransformer());
    }


    /**
     * 编辑节目授权
     * @param ProjectAuthRequest $projectAuthRequest
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     */
    public function update(ProjectAuthRequest $projectAuthRequest, ProjectAuth $projectAuth): Response
    {

        $updateParams = $this->checkAndGetParams($projectAuthRequest);

        if (ProjectAuth::query()->where($updateParams)->where('id', '!=', $projectAuth->id)->first()) {
            abort(422, '该授权对象已经授权过该节目及皮肤');
        }

        $projectAuth->update($updateParams);

        activity('update_project_auth')
            ->causedBy($this->user())
            ->performedOn($projectAuth)
            ->withProperties(['ip' => $projectAuthRequest->getClientIp(), 'request_params' => $projectAuthRequest->all()])
            ->log('编辑节目授权');


        return $this->response->item($projectAuth, new ProjectAuthTransformer());

    }

    /**
     * 新增与更新的参数判断
     * @param $projectAuthRequest
     * @return array
     */
    private function checkAndGetParams(Request $projectAuthRequest): array
    {
        /** @var Customer $customer */
        $customer = Customer::query()->find($projectAuthRequest->get('customer_id'));
        $pid = $projectAuthRequest->get('project_id');
        $bid = $projectAuthRequest->get('skin_id');

        if ($bid !== 0) {
            /** @var Skin $skin */
            $skin = Skin::query()->where('bid', '=', $bid)
                ->where('pass', '=', 1)
                ->where('piid', '=', $pid)
                ->first();

            if (!$skin) {
                abort(422, '授权的皮肤不属于对应的节目 或者该皮肤未通过审核');
            }
        }
        
        if (!$customer->hasRole('market_owner')) {
            abort(422, '授权对象不是场地主');
        }

        if (!$customer->z) {
            abort(422, '授权对象不存在z值');
        }

        return [
            'z' => $customer->z,
            'pid' => $pid,
            'bid' => $bid,
        ];
    }

    /**
     * 删除节目授权
     * @param ProjectAuth $projectAuth
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function destroy(ProjectAuth $projectAuth, Request $request): Response
    {
        $projectAuth->delete();

        activity('delete_project_auth')
            ->causedBy($this->user())
            ->performedOn($projectAuth)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('删除节目授权');

        return $this->response()->noContent();
    }
}
