<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TeamProjectBugRecordTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['team_project', 'recorder'];

    public function transform(TeamProjectBugRecord $teamProjectBugRecord)
    {
        $items = TeamProjectBugRecord::with("user")
            ->where("team_project_id", $teamProjectBugRecord->team_project_id)
            ->where("date", $teamProjectBugRecord->date)
            ->get()->map(function ($value) {
                return [
                    "user_id" => $value->user_id,
                    "duty" => $value->duty,
                    "name" => $value->user ? $value->user->name : $value->user_id
                ];
            })->groupBy("duty")->toArray();

        return [
            'id' => $teamProjectBugRecord->id,
            'team_project_id' => $teamProjectBugRecord->team_project_id,
            'project_name' => $teamProjectBugRecord->project_name,
            'belong' => $teamProjectBugRecord->belong,
            'occur_date' => Carbon::parse($teamProjectBugRecord->occur_date)->timezone('PRC')->toDateString(),
            'recorder_id' => $teamProjectBugRecord->recorder_id,
            'description' => $teamProjectBugRecord->description,
            'created_at' => Carbon::parse($teamProjectBugRecord->created_at)->timezone('PRC')->toDateTimeString(),
            'updated_at' => Carbon::parse($teamProjectBugRecord->updated_at)->timezone('PRC')->toDateTimeString(),
            "tester" => $items['tester_quality'] ? array_column($items['tester_quality'], 'name') : [],
            "tester_text" => $items['tester_quality'] ? implode(",", array_column($items['tester_quality'], 'name')) : '',
            "operation" => $items['operation_quality'] ? array_column($items['operation_quality'], 'name') : [],
            "operation_text" => $items['operation_quality'] ? implode(",", array_column($items['operation_quality'], 'name')) : '',
        ];
    }

    public function includeTeamProject(TeamProjectBugRecord $teamProjectBugRecord)
    {
        $teamProject = $teamProjectBugRecord->team_project;
        if ($teamProject) {
            return $this->item($teamProject, new TeamProjectTransformer());
        }
        return null;
    }

    public function includeRecorder(TeamProjectBugRecord $teamProjectBugRecord)
    {
        $recorder = $teamProjectBugRecord->recorder;
        if ($recorder) {
            return $this->item($recorder, new UserTransformer());
        }
        return null;
    }

}