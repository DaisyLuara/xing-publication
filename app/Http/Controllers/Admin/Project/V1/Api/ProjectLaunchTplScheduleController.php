<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTplScheduleTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectLaunchTplScheduleRequest;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule;
use App\Http\Controllers\Controller;

class ProjectLaunchTplScheduleController extends Controller
{
    public function store(ProjectLaunchTplScheduleRequest $request, ProjectLaunchTplSchedule $schedule)
    {
        $fillData = $this->convert($request->all());
        $schedule->fill($fillData)->save();
        return $this->response->item($schedule, new ProjectLaunchTplScheduleTransformer())
            ->setStatusCode(201);
    }

    public function update($id, ProjectLaunchTplScheduleRequest $request, ProjectLaunchTplSchedule $schedule)
    {
        $updateData = $this->convert($request->except('tpl_id'));
        $schedule->query()
            ->where($schedule->getKeyName(), $id)
            ->update($updateData);
        $schedule = $schedule->find($id);
        return $this->response->item($schedule, new ProjectLaunchTplScheduleTransformer())
            ->setStatusCode(201);
    }

    private function convert(array $input)
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

        return $data;
    }
}
