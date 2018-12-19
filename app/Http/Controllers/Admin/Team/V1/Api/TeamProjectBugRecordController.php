<?php

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord;
use App\Http\Controllers\Admin\Team\V1\Request\TeamProjectBugRecordRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectBugRecordTransformer;
use App\Http\Controllers\Controller;

class TeamProjectBugRecordController extends Controller
{
    public function show(TeamProjectBugRecord $teamProjectBugRecord)
    {
        return $this->response()->item($teamProjectBugRecord, new TeamProjectBugRecordTransformer());
    }

    public function index(TeamProjectBugRecord $teamProjectBugRecord)
    {
        $query = $teamProjectBugRecord->query();
        $teamProjectBugRecord = $query->paginate(10);
        return $this->response()->paginator($teamProjectBugRecord, new TeamProjectBugRecordTransformer());
    }

    public function store(TeamProjectBugRecordRequest $request, TeamProjectBugRecord $teamProjectBugRecord)
    {
        $user = $this->user;
        $teamProject = TeamProject::findOrFail($request->team_project_id);

        $teamProjectBugRecord->fill((array_merge($request->all(),
            [
                'recorder_id' => $user->id,
                'project_name' => $teamProject->project_name,
                'belong'=>$teamProject->belong
            ]
        )))->save();

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(TeamProjectBugRecordRequest $request, TeamProjectBugRecord $teamProjectBugRecord)
    {
        $user = $this->user;
        $params = $request->all();
        $teamProject = TeamProject::findOrFail($params['team_project_id']);
        if($teamProjectBugRecord->status == 1){
            abort("403","该条记录已用于绩效统计，不可更新");
        }
        $params['project_name'] = $teamProject->project_name;
        $params['belong'] = $teamProject->belong;
        $params['recorder_id'] = $user->id;

        $teamProjectBugRecord->update($params);

        return $this->response()->noContent()->setStatusCode(200);
    }
}