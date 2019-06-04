<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectRequest;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ProjectController extends Controller
{
    public function index(Request $request, Project $project)
    {
        $query = $project->query();
        $user = $this->user();

        /**
         * 场地住登陆时 需要根据 公司节目授权 获取节目列表
         * @todo 需要新增商户平台
         */
        if ($user->hasRole('market_owner')) {
            $query->whereHas('company', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            });
        } else {
            $arUserZ = getArUserZ($user, $request);
            if ($arUserZ) {
                $query->whereHas('points', function ($q) use ($arUserZ) {
                    $q->where('bd_z', '=', $arUserZ);
                });
            }
        }
        if ($request->alias) {
            $query->where('versionname', '=', $request->alias);
        }
        $project = $query->where('name', 'like', "%{$request->name}%")
            ->orderBy('clientdate', 'desc')
            ->paginate(10);
        return $this->response->paginator($project, new ProjectTransformer());
    }

    public function store(ProjectRequest $request, Project $project)
    {
        $data = $request->all();
        $names = explode(PHP_EOL, $request->name);
        unset($data['name']);

        $query = $project->query();
        foreach ($names as $name) {
            $query->create(array_merge(['name' => $name, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        }

        activity('create_product')
            ->causedBy($this->user())
            ->performedOn($project)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增节目');

        return $this->response->noContent();
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->except(['policy_id']);
        $data['policy_id'] = $request->policy_id ?? 0;

        $ids = $request->ids;
        unset($data['ids']);

        foreach ($ids as $id) {
            $project->query()->where('id', '=', $id)->update($data);
        }

        activity('update_product')
            ->causedBy($this->user())
            ->performedOn($project)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑节目');

        return $this->response->noContent();
    }
}
