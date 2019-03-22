<?php

namespace App\Http\Controllers\Admin\ResourceAuth\V1\Api;

use App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth;
use App\Http\Controllers\Admin\ResourceAuth\V1\Request\ProjectAuthRequest;
use App\Http\Controllers\Admin\ResourceAuth\V1\Transformer\ProjectAuthTransformer;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class ProjectAuthController extends Controller
{
    /**
     * 节目授权列表
     * @param Request $request
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, ProjectAuth $projectAuth)
    {
        $query = $projectAuth->query();


        if ($request->has('project_id') && $request->get('project_id')) {
            $query->where('pid', $request->get('project_id'));
        }

        if ($request->has('customer_id') && $request->get('customer_id')) {
            $customer = Customer::query()->find($request->get('customer_id'));
            $query->where('z', $customer->z);
        }
        $projectAuth = $query->orderByDesc('id')->paginate(10);

        return $this->response->paginator($projectAuth, new ProjectAuthTransformer());
    }

    /**
     * 节目授权详情
     * @param Request $request
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     */
    public function show(Request $request, ProjectAuth $projectAuth)
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
    public function store(ProjectAuthRequest $projectAuthRequest, ProjectAuth $projectAuth)
    {
        $insertParams = self::checkAndGetParams($projectAuthRequest);

        if ($projectAuth->query()->where($insertParams)->first()) {
            abort(422, '该授权对象已经授权过该节目');
        }

        $projectAuth->fill($insertParams)->save();

        return $this->response->item($projectAuth, new ProjectAuthTransformer());
    }


    /**
     * 编辑节目授权
     * @param ProjectAuthRequest $projectAuthRequest
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     */
    public function update(ProjectAuthRequest $projectAuthRequest, ProjectAuth $projectAuth)
    {

        $updateParams = self::checkAndGetParams($projectAuthRequest);

        if (ProjectAuth::query()->where($updateParams)->where('id', '!=', $projectAuth->id)->first()) {
            abort(422, '该授权对象已经授权过该节目');
        }

        $projectAuth->update($updateParams);

        return $this->response->item($projectAuth, new ProjectAuthTransformer());

    }

    /**
     * 新增与更新的参数判断
     * @param $projectAuthRequest
     * @return array
     */
    private function checkAndGetParams($projectAuthRequest)
    {
        /** @var Customer $customer */
        $customer = Customer::query()->find($projectAuthRequest->get('customer_id'));
        $pid = $projectAuthRequest->get('project_id');

        if (!$customer->hasRole('market_owner')) {
            abort(422, '授权对象不是场地主');
        }

        if (!$customer->z) {
            abort(422, '授权对象不存在z值');
        }

        return [
            'z' => $customer->z,
            'pid' => $pid
        ];
    }

    /**
     * 删除节目授权
     * @param ProjectAuth $projectAuth
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function destroy( ProjectAuth $projectAuth)
    {
        $projectAuth->delete();
        return $this->response()->noContent();
    }
}
