<?php

namespace App\Http\Controllers\Admin\Team\V1\Api;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Request\TeamProjectRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectListTransformer;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeamProjectController extends Controller
{
    /**
     * 项目详情
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function show(TeamProject $teamProject)
    {
        return $this->response()->item($teamProject, new TeamProjectTransformer());
    }

    /**
     * 项目列表
     * @param TeamProjectRequest $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function index(TeamProjectRequest $request, TeamProject $teamProject)
    {
        $query = $teamProject->query();
        if ($request->has('alias')) {
            $query->where('belong', $request->alias);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('start_date_begin') && $request->has('end_date_begin')) {
            $query->whereRaw("begin_date between '$request->start_date_begin' and '$request->end_date_begin' ");
        }
        if ($request->has('start_date_online') && $request->has('end_date_online')) {
            $query->whereRaw("online_date between '$request->start_date_online' and '$request->end_date_online' ");
        }
        if ($request->has('start_date_launch') && $request->has('end_date_launch')) {
            $query->whereRaw("launch_date between '$request->start_date_launch' and '$request->end_date_launch' ");
        }
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if (($request->has('own') && $request->own == 'true')
            ||
            !$user->hasRole('tester|operation|legal-affairs-manager|bonus-manager')
        ) {
            $query->where(function ($query) use ($user) {
                $query->where('applicant', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereHas('member', function ($q) use ($user) {
                            $q->where('id', $user->id);
                        });
                    });
            });
        }

        $teamProject = $query->orderBy('begin_date', 'desc')->paginate(10);

        return $this->response()->paginator($teamProject, new TeamProjectListTransformer());
    }

    /**
     * 保存项目
     * @param TeamProjectRequest $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function store(TeamProjectRequest $request, TeamProject $teamProject)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $params = $request->all();

        $member = $params['member'];
        if ($member['tester'] || $member['tester_quality']) {
            $tester_ids = array_column($member['tester'] ?? [], 'user_id');
            $tester_quality_ids = array_column($member['tester_quality'] ?? [], 'user_id');
            if (count($tester_ids) != count($tester_quality_ids) || array_diff($tester_quality_ids, $tester_ids) || array_diff($tester_ids, $tester_quality_ids)) {
                abort("422", "tester与tester_quality人员需一致");
            }
        }
        if ($member['operation'] || $member['operation_quality']) {
            $operation_ids = array_column($member['operation'] ?? [], 'user_id');
            $operation_quality_ids = array_column($member['operation_quality'] ?? [], 'user_id');
            if (count($operation_ids) != count($operation_quality_ids) || array_diff($operation_quality_ids, $operation_ids) || array_diff($operation_ids, $operation_quality_ids)) {
                abort("422", "operation与operation_quality人员需一致");
            }
        }

        $project = Project::query()->where('versionname', $params['belong'])->first();

        $params['status'] = 1;
        $params['applicant'] = $user->id;
        $params['begin_date'] = Carbon::now()->toDateString();
        $params['project_name'] = $project->name;
        $params['launch_date'] = $project->online != 0 ? date('Y-m-d', $project->online / 1000) : null;
        $params['interaction_attribute'] = implode(",", $params['interaction_attribute']);

        $teamProject->fill($params)->save();
        $this->memberStore($member, $teamProject);
        return $this->response()->noContent()->setStatusCode(201);

    }

    /**
     * 保存对应项目成员
     * @param $member
     * @param TeamProject $teamProject
     */
    private function memberStore($member, TeamProject $teamProject)
    {
        foreach ($member as $key => $value) {
            foreach ($value as $item) {
                $teamProject->member()->attach($item['user_id'], ['user_name' => $item['user_name'], 'type' => $key, 'rate' => $item['rate']]);
            }
        }
    }

    /**
     * 修改项目
     * @param TeamProjectRequest $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function update(TeamProjectRequest $request, TeamProject $teamProject)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('project-manager') && $teamProject->status > 2) {
            abort(403, '项目已无法修改');
        }

        $params = $request->all();

        $member = $params['member'];
        if ($member['tester'] || $member['tester_quality']) {
            $tester_ids = array_column($member['tester'] ?? [], 'user_id');
            $tester_quality_ids = array_column($member['tester_quality'] ?? [], 'user_id');
            if (count($tester_ids) != count($tester_quality_ids) || array_diff($tester_quality_ids, $tester_ids) || array_diff($tester_ids, $tester_quality_ids)) {
                abort("422", "tester与tester_quality人员需一致");
            }
        }
        if ($member['operation'] || $member['operation_quality']) {
            $operation_ids = array_column($member['operation'] ?? [], 'user_id');
            $operation_quality_ids = array_column($member['operation_quality'] ?? [], 'user_id');
            if (count($operation_ids) != count($operation_quality_ids) || array_diff($operation_quality_ids, $operation_ids) || array_diff($operation_ids, $operation_quality_ids)) {
                abort("422", "operation与operation_quality人员需一致");
            }
        }

        $project = Project::query()->where('versionname', $params['belong'])->first();

        unset($params['applicant']);
        unset($params['begin_date']);
        unset($params['online_date']);
        unset($params['status']);
        unset($params['status']);
        $params['project_name'] = $project->name;
        $params['launch_date'] = $project->online != 0 ? date('Y-m-d', $project->online / 1000) : null;
        $teamProject->update($params);


        $teamProject->member()->detach();
        $this->memberStore($member, $teamProject);
        return $this->response()->noContent()->setStatusCode(200);
    }


    /**
     * 测试、运营、主管确认
     * @param Request $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function confirm(Request $request, TeamProject $teamProject)
    {

        /** @var  $user \App\Models\User */
        $user = $this->user();

        if ($user->hasRole('tester') && $teamProject->status == 1) {
            $media = Media::find($request->get('media_id')??0);
            if(!$media){
                abort("422","请上传测试用例");
            }
            $teamProject->status = 2;
            $teamProject->tester_media_id = $media->id;
            $teamProject->update();
            return $this->response()->noContent()->setStatusCode(200);
        }

        if ($user->hasRole('operation') && $teamProject->status == 2) {
            $media = Media::find($request->get('media_id')??0);
            if(!$media){
                abort("422","请上传运营文档");
            }
            $teamProject->status = 3;
            $teamProject->operation_media_id = $media->id;
            $teamProject->update();
            return $this->response()->noContent()->setStatusCode(200);
        }

        if (($user->hasRole('legal-affairs-manager') || $user->hasRole('bonus-manager')) && $teamProject->status == 3 && $teamProject->type == 1) {
            $teamProject->status = 4;
            $teamProject->online_date = Carbon::now()->toDateString();
            $teamProject->update();
            return $this->response()->noContent()->setStatusCode(200);
        }
        abort(403, '无操作权限');
    }
}
