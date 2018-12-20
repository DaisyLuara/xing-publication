<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TeamProjectBugRecordTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['team_project,recorder'];

    public function transform(TeamProjectBugRecord $teamProjectBugRecord)
    {

        return [
            'team_project_id' => $teamProjectBugRecord->team_project_id,
            'project_name' => $teamProjectBugRecord->project_name,
            'belong' => $teamProjectBugRecord->belong,
            'bug_num' => $teamProjectBugRecord->bug_num,
            'user_id' => $teamProjectBugRecord->user_id,
            'duty' => $teamProjectBugRecord->duty,
            'date' => Carbon::parse($teamProjectBugRecord->date)->timezone('PRC')->toDateString(),
            'recorder_id' => $teamProjectBugRecord->recorder_id,
            'description' => $teamProjectBugRecord->description,
            'created_at'=> Carbon::parse($teamProjectBugRecord->created_at)->timezone('PRC')->toDateTimeString(),
            'updated_at'=> Carbon::parse($teamProjectBugRecord->updated_at)->timezone('PRC')->toDateTimeString(),
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