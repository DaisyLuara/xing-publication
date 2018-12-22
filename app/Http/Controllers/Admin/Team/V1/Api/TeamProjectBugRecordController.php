<?php

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord;
use App\Http\Controllers\Admin\Team\V1\Request\TeamProjectBugRecordRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectBugRecordListTransformer;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectBugRecordTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamProjectBugRecordController extends Controller
{
    public function show(TeamProjectBugRecord $teamProjectBugRecord)
    {
        return $this->response()->item($teamProjectBugRecord, new TeamProjectBugRecordTransformer());
    }

    /**
     * 重大事件列表页
     * @param Request $request
     * @param TeamProjectBugRecord $teamProjectBugRecord
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, TeamProjectBugRecord $teamProjectBugRecord)
    {
        $query = $teamProjectBugRecord->query();

        if ($request->has("alias") && $request->alias) {
            $query = $query->where('belong', $request->alias);
        }

        if ($request->has("start_occur_date") && $request->has("end_occur_date")) {
            $query->whereBetween("occur_date", [
                Carbon::parse($request->start_occur_date)->toDateString(),
                Carbon::parse($request->end_occur_date)->toDateString()
            ]);
        }

        $teamProjectBugRecord = $query->groupBy("team_project_id", "occur_date")->paginate(10);

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
        $belong = $request->belong;
        $occur_date = Carbon::parse($request->occur_date)->timezone("PRC")->toDateString();
        $description = $request->description;
        $current_user_id = $this->user->id;

        //记录日期默认是下一个季度第一天
        $date = Carbon::now()->addQuarter()->startOfQuarter()->toDateString();
        if ($occur_date >= $date || $occur_date < Carbon::parse($date)->subMonths(3)->toDateString()) {
            abort(422, "事件发生时间只能是本季度");
        }

        //项目(项目必须是已经被确认了的，状态为3或者4)
        $teamProject = TeamProject::where('belong', $belong)->first();
        if (!$teamProject || !in_array($teamProject->status, [3, 4])) {
            abort(422, "该项目还未被确认，暂时无法添加重大事件");
        }

        //如果该项目该日期已经存在责任记录，则不能添加只能修改
        $recordItem = $teamProjectBugRecord->query()
            ->where("belong", $belong)
            ->where('occur_date', $occur_date)
            ->first();
        if ($recordItem) {
            abort(422, "该节目该日期已经存在重大责任，不可再添加，请前往修改或者填写其他日期");
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
                'bug_num' => 1,
                'occur_date' => $occur_date,
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
     * 批量更新
     * @param TeamProjectBugRecordRequest $request
     * @param TeamProjectBugRecord $teamProjectBugRecord
     * @return \Dingo\Api\Http\Response
     */
    public function update(TeamProjectBugRecordRequest $request, TeamProjectBugRecord $teamProjectBugRecord)
    {
        $belong = $request->belong;
        $occur_date = Carbon::parse($request->occur_date)->timezone("PRC")->toDateString();
        $description = $request->description;
        $current_user_id = $this->user->id;

        //新项目判断(项目必须是已经被确认了的，状态为3或者4)
        $teamProject = TeamProject::where('belong', $belong)->first();
        if (!$teamProject || !in_array($teamProject->status, [3, 4])) {
            abort(422, "该项目还未被确认，暂时无法添加重大事件");
        }

        //默认是下一个季度第一天
        $date = Carbon::now()->addQuarter()->startOfQuarter()->toDateString();
        if (Carbon::parse($teamProjectBugRecord->occur_date)->startOfQuarter()->addMonths(3)->toDateString() != $date) {
            abort(422, "不是本季度的重大责任不可再修改");
        }
        if ($occur_date >= $date || $occur_date < Carbon::parse($date)->subMonths(3)->toDateString()) {
            abort(422, "事件发生时间只能是本季度");
        }


        try {
            DB::BeginTransaction();
            //删除原本的重大责任
            $result[] = DB::table("team_project_bug_records")
                ->where("belong", $teamProjectBugRecord->belong)
                ->where("occur_date", $teamProjectBugRecord->occur_date)
                ->delete();

            //添加新的重大责任
            //如果该项目该日期已经存在责任记录，则不能添加只能修改
            $recordItem = $teamProjectBugRecord->query()
                ->where("belong", $belong)
                ->where('occur_date', $occur_date)
                ->first();
            if ($recordItem) {
                DB::rollBack();
                abort(422, "该节目该日期已经存在重大责任，不可再添加，请前往修改或者填写其他日期");
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
                    'bug_num' => 1,
                    'occur_date' => $occur_date,
                    'date' => $date,
                    'recorder_id' => $current_user_id,
                    'description' => $description,
                    'created_at' => $now,
                ];
            }

            $result[] = DB::table("team_project_bug_records")->insert($params);

            if (check_arr($result)) {
                DB::commit();
                return $this->response()->noContent()->setStatusCode(200);
            } else {
                abort("500", "更新出错");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            abort("500", $e->getMessage());
        }

    }
}