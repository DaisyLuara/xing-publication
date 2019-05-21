<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectLaunchTplScheduleRequest;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTplScheduleTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;

class ProjectLaunchTplScheduleController extends Controller
{
    public function index(ProjectLaunchTpl $tpl, ProjectLaunchTplSchedule $schedule): Response
    {
        $query = $schedule->query();
        $schedule = $query->where('tvid', $tpl->tvid)->paginate(10);
        return $this->response()->paginator($schedule, new ProjectLaunchTplScheduleTransformer());
    }

    public function store(ProjectLaunchTplScheduleRequest $request, ProjectLaunchTpl $tpl, ProjectLaunchTplSchedule $schedule)
    {
        $fillData = $this->convert($request->all());
        $schedule->fill(array_merge($fillData, ['tvid' => $tpl->tvid]))->save();
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(ProjectLaunchTplScheduleRequest $request, ProjectLaunchTpl $tpl, ProjectLaunchTplSchedule $schedule): Response
    {
        $updateData = $this->convert($request->except('tpl_id'));
        $schedule->update($updateData);
        return $this->response()->noContent();
    }

    private function convert(array $input): array
    {
        $data = [];

        if (isset($input['tpl_id'])) {
            $data['tvid'] = $input['tpl_id'];
        }

        if (isset($input['project_id'])) {
            $data['plid'] = $input['project_id'];
        }

        if (isset($input['date_start'])) {
            $data['shm'] = str_replace(':', '', $input['date_start']);
        }

        if (isset($input['date_end'])) {
            $data['ehm'] = str_replace(':', '', $input['date_end']);
        }

        if (isset($input['bid'])) {
            $data['bid'] = $input['bid'];
        }

        return $data;
    }
}
