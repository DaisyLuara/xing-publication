<?php

namespace App\Http\Controllers\Admin\Team\V1\Api;

use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Request\TeamProjectRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectListTransformer;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamProjectTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeamProjectController extends Controller
{
    public function show(TeamProject $teamProject)
    {
        return $this->response()->item($teamProject, new TeamProjectTransformer());
    }

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
            $query->whereRaw("begin_date between '$request->start_date_online' and '$request->end_date_online' ");
        }
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if (!$user->hasRole('tester') && !$user->hasRole('operation') && !$user->hasRole('legal-affairs-manager') && !$user->hasRole('bonus-manager')) {
            $query->where(function ($query) use ($user) {
                $query->where('applicant', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereHas('member', function ($q) use ($user) {
                            $q->where('id', $user->id);
                        });
                    });
            });
        }

        if ($request->has('own') && $request->own == 'true') {
            $query->where(function ($query) use ($user) {
                $query->where('applicant', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereHas('member', function ($q) use ($user) {
                            $q->where('id', $user->id);
                        });
                    });
            });
        }

        $teamProject = $query->paginate(10);

        return $this->response()->paginator($teamProject, new TeamProjectListTransformer());
    }

    public function store(TeamProjectRequest $request, TeamProject $teamProject)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('project-manager')) {
            abort(403, '无操作权限');
        }
        if ($request->project_attribute <= 2) {
            $factor = 0.8;
        } else {
            $factor = 1;
        }
        $teamProject->fill((array_merge($request->all(),
            [
                'status' => 1,
                'applicant' => $user->id,
                'begin_date' => Carbon::now()->toDateString(),
                'factor' => $factor
            ]
        )))->save();
        $member = $request->member;
        $this->memberStore($member, $teamProject);
        return $this->response()->noContent()->setStatusCode(201);

    }

    private function memberStore($member, TeamProject $teamProject)
    {
        foreach ($member as $key => $value) {
            foreach ($value as $item) {
                $teamProject->member()->attach($item['user_id'], ['user_name' => $item['user_name'], 'type' => $key, 'rate' => $item['rate']]);
            }
        }

    }

    public function update(TeamProjectRequest $request, TeamProject $teamProject)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('project-manager') && !$user->hasRole('legal-affairs-manager')) {
            abort(403, '无操作权限');
        }
        if ($user->hasRole('project-manager') && $teamProject->status > 2) {
            abort(403, '项目已无法修改');
        }
        $teamProject->update($request->all());
        $member = $request->member;
        $teamProject->member()->detach();
        $this->memberStore($member, $teamProject);
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function confirm(Request $request, TeamProject $teamProject)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if ($user->hasRole('tester') && $teamProject->status == 1) {
            $teamProject->status = 2;
            $teamProject->update();
            return $this->response()->noContent()->setStatusCode(200);
        }

        if ($user->hasRole('operation') && $teamProject->status == 2) {
            $teamProject->status = 3;
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
