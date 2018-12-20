<?php

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord;
use App\Http\Controllers\Admin\Team\V1\Request\TeamProjectBugRecordRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectBugRecordTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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

    /**
     * 通过项目批量添加重大责任/bug
     * @param TeamProjectBugRecordRequest $request
     * @param TeamProjectBugRecord $teamProjectBugRecord
     * @return \Dingo\Api\Http\Response
     */
    public function store(TeamProjectBugRecordRequest $request, TeamProjectBugRecord $teamProjectBugRecord)
    {
        $team_project_id = $request->team_project_id;
        $bug_num = (int)($request->bug_num ?? 0);
        $description = $request->description;
        $current_user_id = $this->user->id;

        //默认是下一个季度第一天
        $date = Carbon::now()->addQuarter()->startOfQuarter()->toDateString();

        //项目(项目必须是已经被确认了的，状态为3或者4)
        $teamProject = TeamProject::find($team_project_id);
        if (!in_array($teamProject->status, [3, 4])) {
            abort(422, "该项目还未被确认，暂时无法添加重大事件");
        }

        //如果该项目该日期已经存在记录，则不能添加只能修改
        $recordItem = $teamProjectBugRecord->query()
            ->where("team_project_id", $team_project_id)
            ->where('date', $date)
            ->first();
        if ($recordItem) {
            abort(422, "该项目该季度已经存在重大事件，不可再添加，请前往修改");
        }

        //得到项目对应的测试与运营人员
        $members = $teamProject->member()->wherePivotIn('type', ['tester_quality', 'operation_quality'])->get();

        //得到插入bug记录中的数组
        $params = [];
        $now = Carbon::now('PRC');
        foreach ($members as $member) {
            $params[] = [
                'team_project_id' => $teamProject->id,
                'project_name' => $teamProject->project_name,
                'belong' => $teamProject->belong,
                'user_id' => $member->id,
                'duty' => $member->pivot ? $member->pivot->type : null,
                'bug_num' => $bug_num,
                'date' => $date,
                'recorder_id' => $current_user_id,
                'description' => $description,
                'created_at' => $now,
            ];
        }

        $teamProjectBugRecord->query()->insert($params);

        return $this->response()->noContent()->setStatusCode(201);
    }

    /**
     * 批量更新 bug数量
     * @param TeamProjectBugRecordRequest $request
     * @param TeamProjectBugRecord $teamProjectBugRecord
     * @return \Dingo\Api\Http\Response
     */
    public function update(TeamProjectBugRecordRequest $request, TeamProjectBugRecord $teamProjectBugRecord)
    {
        $team_project_id = $request->team_project_id;
        $bug_num = (int)($request->bug_num ?? 0);
        $description = $request->description;
        $current_user_id = $this->user->id;

        //默认是下一个季度第一天
        $date = Carbon::now()->addQuarter()->startOfQuarter()->toDateTimeString();
        //项目(项目必须是已经被确认了的，状态为3或者4)
        $teamProject = TeamProject::find($team_project_id);
        if (!in_array($teamProject->status, [3, 4])) {
            abort(422, "该项目还未被确认，暂时无法添加重大事件");
        }

        $teamProjectBugRecord->query()
            ->where("team_project_id", $team_project_id)
            ->where('date', $date)
            ->update([
                'bug_num' => $bug_num,
                'description' => $description,
                'recorder_id' => $current_user_id]);

        return $this->response()->noContent()->setStatusCode(200);
    }
}