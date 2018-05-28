<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;


use App\Http\Requests\Api\V1\ProjectAdLaunchRequest;
use App\Models\ProjectAdLaunch;
use App\Transformers\ProjectAdLaunchTransformer;
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
        return $this->response->noContent();
    }
}
