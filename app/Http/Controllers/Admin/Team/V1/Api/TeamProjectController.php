<?php

namespace App\Http\Controllers\Admin\Team\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Request\TeamProjectRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectListTransformer;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Common\V1\Request\ExportRequest;

class TeamProjectController extends Controller
{
    /**
     * 项目详情
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function show(TeamProject $teamProject): Response
    {
        return $this->response()->item($teamProject, new TeamProjectTransformer());
    }

    /**
     * 项目列表
     * @param TeamProjectRequest $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function index(TeamProjectRequest $request, TeamProject $teamProject): Response
    {
        $query = $teamProject->query();
        if ($request->has('alias')) {
            $query->where('belong', $request->get('alias'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($request->get('start_date_begin') && $request->get('end_date_begin')) {
            $query->whereRaw("begin_date between '".$request->get('start_date_begin')."' and '".$request->get('end_date_begin')."' ");
        }
        if ($request->get('start_date_online') && $request->get('end_date_online')) {
            $query->whereRaw("online_date between '".$request->get('start_date_online')."' and '".$request->get('end_date_online')."' ");
        }
        if ($request->get('start_date_launch') && $request->get('end_date_launch')) {
            $query->whereRaw("launch_date between '".$request->get('start_date_launch')."' and '".$request->get('end_date_launch')."' ");
        }
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if ($request->get('own')
            ||
            !$user->hasRole('tester|operation|operation-assistant|legal-affairs-manager|bonus-manager')
        ) {
            $query->where(static function ($query) use ($user) {
                $query->where('applicant', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereHas('member', static function ($q) use ($user) {
                            $q->where('id', $user->id);
                        });
                    });
            });
        }

        $teamProjects = $query->orderBy('created_at', 'desc')->paginate(10);

        return $this->response()->paginator($teamProjects, new TeamProjectListTransformer());
    }

    /**
     * 保存项目
     * @param TeamProjectRequest $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function store(TeamProjectRequest $request, TeamProject $teamProject): Response
    {

        $params = $request->all();
        $member = $request->member ?? [];
        $this->checkParams($request);
        $params = $this->dealParams($params, 'create');

        $teamProject->fill($params)->save();
        $this->memberStore($member, $teamProject);

        activity('create_team_project')
            ->causedBy($this->user())
            ->performedOn($teamProject)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增节目智造');

        return $this->response()->noContent()->setStatusCode(201);

    }

    /**
     * 保存对应项目成员
     * @param $member
     * @param TeamProject $teamProject
     */
    private function memberStore($member, TeamProject $teamProject): void
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
    public function update(TeamProjectRequest $request, TeamProject $teamProject): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($teamProject->status > 2 && $user->hasRole('project-manager')) {
            abort(403, '项目已确认，无法修改');
        }

        $params = $request->all();
        $member = $request->member ?? [];

        $this->checkParams($request);
        $params = $this->dealParams($params, 'update', $teamProject->id);

        $teamProject->update($params);

        $teamProject->member()->detach();
        $this->memberStore($member, $teamProject);

        activity('update_team_project')
            ->causedBy($this->user())
            ->performedOn($teamProject)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑节目智造');

        return $this->response()->noContent()->setStatusCode(200);
    }

    /**
     * 处理参数
     *
     * @param $params
     * @param null $type
     * @param int $team_project_id
     * @return mixed
     */
    public function dealParams($params, $type = null, $team_project_id = 0)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $project = Project::query()->where('versionname', $params['belong'])->first();

        if (!$project) {
            abort(422, '找不到' . $params['belong'] . '对应的节目');
        }

        if ($params['copyright_attribute'] === 0) {
            $params['copyright_project_id'] = null;
        }

        if ($params['individual_attribute'] === 0) {
            $params['contract_id'] = null;
        } else {
            //测试所选的合同的节目数量是否已达到上线
            $contract = Contract::query()->find($params['contract_id']);

            //查询合同关联的定制普通节目数与特别节目数是否达上线
            $team_projects_num = TeamProject::query()->where('contract_id', $contract->id)
                ->where('id', '!=', $team_project_id)
                ->whereIn('individual_attribute', [1, 2])
                ->groupBy('individual_attribute')
                ->selectRaw('individual_attribute,count(*) as num')
                ->pluck('num', 'individual_attribute')->toArray();

            if ($params['individual_attribute'] === 1 && ($team_projects_num[1] ?? 0) >= $contract->special_num) {
                abort(422, '该合同的定制特别节目数量已达上线' . $contract->special_num . '个');
            } else if ($params['individual_attribute'] === 2 && ($team_projects_num[2] ?? 0) >= $contract->common_num) {
                abort(422, '该合同的定制通用节目数量已达上线' . $contract->common_num . '个');
            }
        }

        if ($type === 'create') {
            $params['status'] = 1;
            $params['applicant'] = $user->id;
            $params['begin_date'] = Carbon::now()->toDateString();
        } else if ($type === 'update') {
            if (isset($params['tester_media_id']) && !$params['tester_media_id']) {
                unset($params['tester_media_id']);
            }
            if (isset($params['test_remark']) && !$params['test_remark']) {
                unset($params['test_remark']);
            }
            unset($params['applicant'], $params['begin_date'], $params['online_date'], $params['status']);
        }

        $params['project_name'] = $project->name;
        $params['launch_date'] = $project->online !== 0 ? date('Y-m-d', $project->online / 1000) : null;
        $params['interaction_attribute'] = implode(',', $params['interaction_attribute'] ?? []);

        return $params;
    }


    /**
     * 更新、保存的参数判断
     * @param $request
     */
    public function checkParams($request): void
    {
        $member = $request->member ?? [];
        if (isset($member['tester']) || isset($member['tester_quality'])) {
            $tester_ids = array_column($member['tester'] ?? [], 'user_id');
            $tester_quality_ids = array_column($member['tester_quality'] ?? [], 'user_id');
            if (array_diff($tester_quality_ids, $tester_ids) || array_diff($tester_ids, $tester_quality_ids)) {
                abort(422, 'tester与tester_quality人员需一致');
            }
        }
        if (isset($member['operation']) || isset($member['operation_quality'])) {
            $operation_ids = array_column($member['operation'] ?? [], 'user_id');
            $operation_quality_ids = array_column($member['operation_quality'] ?? [], 'user_id');
            if (array_diff($operation_quality_ids, $operation_ids) || array_diff($operation_ids, $operation_quality_ids)) {
                abort(422, 'operation与operation_quality人员需一致');
            }
        }

    }


    /**
     * 测试、运营、主管确认
     * @param Request $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function confirm(Request $request, TeamProject $teamProject): ?Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $params= $request->all();
        if ($teamProject->status === 1 && $user->hasRole('tester')) {
            $media = Media::find($request->get('media_id') ?? 0);
            if (!$media) {
                abort(422, '请上传测试用例');
            }
            if ($request->get('test_remark')) {
                $teamProject->test_remark = $request->get('test_remark');
            }
            $teamProject->status = 2;
            $teamProject->tester_media_id = $media->id;
            $teamProject->update();

            activity('tester_confirm_team_project')
                ->causedBy($user)
                ->performedOn($teamProject)
                ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $params])
                ->log('测试确认节目智造');

            return $this->response()->noContent()->setStatusCode(200);
        }

        if ($teamProject->status === 2 && $user->hasRole('operation|operation-assistant')) {
            $teamProject->status = 3;
            $teamProject->update();

            activity('operator_confirm_team_project')
                ->causedBy($user)
                ->performedOn($teamProject)
                ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $params])
                ->log('运营确认节目智造');

            return $this->response()->noContent()->setStatusCode(200);
        }

        if ( $teamProject->status === 3 && $teamProject->type === 1 && ($user->hasRole('legal-affairs-manager') || $user->hasRole('bonus-manager')) ) {
            $teamProject->status = 4;
            $teamProject->online_date = Carbon::now()->toDateString();
            $teamProject->update();
            activity('bonus_confirm_team_project')
                ->causedBy($user)
                ->performedOn($teamProject)
                ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $params])
                ->log('绩效主管确认节目智造');
            return $this->response()->noContent()->setStatusCode(200);
        }
        abort(403, '无操作权限');
    }


    public function export(ExportRequest $request)
    {
        return excelExport($request);
    }
}
