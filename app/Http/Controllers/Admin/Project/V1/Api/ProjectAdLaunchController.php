<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectAdLaunchTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectAdLaunchRequest;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectAdLaunchController extends Controller
{
    public function index(Request $request, ProjectAdLaunch $projectAdLaunch)
    {
        $query = $projectAdLaunch->query();
        if ($request->project_id) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('id', '=', $request->project_id);
            });
        }

        if ($request->visiable) {
            $query->where('visiable', $request->visiable);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        $projectAdLaunch = $query->paginate(10);

        return $this->response->paginator($projectAdLaunch, new ProjectAdLaunchTransformer());
    }

    public function store(ProjectAdLaunchRequest $request, ProjectAdLaunch $projectAdLaunch)
    {
        $data = $request->all();
        $oids = explode(',', $request->oids);
        unset($data['oids']);

        $query = $projectAdLaunch->query();
        foreach ($oids as $oid) {
            $query->create(array_merge(['oid' => $oid, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        }

        activity('create_product_ad_launch')
            ->causedBy($this->user())
            ->performedOn($projectAdLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增节目广告投放');

        return $this->response->noContent();
    }

    public function update(ProjectAdLaunchRequest $request, ProjectAdLaunch $projectAdLaunch)
    {
        $data = $request->all();
        $adids = $request->adids;
        unset($data['adids']);

        $query = $projectAdLaunch->query();
        foreach ($adids as $adid) {
            $query->where('adid', '=', $adid)->update($data);
        }

        activity('update_product_ad_launch')
            ->causedBy($this->user())
            ->performedOn($projectAdLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑节目广告投放');

        return $this->response->noContent();
    }
}
