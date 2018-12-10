<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;

class ProjectLaunchTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project'];

    public function transform(ProjectLaunch $projectLaunch)
    {
        $data = [
            'id' => $projectLaunch->tvoid,
            'start_date' => date('Y-m-d H:i:s', $projectLaunch->sdate),
            'end_date' => date('Y-m-d H:i:s', $projectLaunch->edate),
            'created_at' => $projectLaunch->date,
            'updated_at' => formatClientDate($projectLaunch->clientdate),
        ];

        $tplIds = collect([
            $projectLaunch->day1_tvid,
            $projectLaunch->day2_tvid,
            $projectLaunch->day3_tvid,
            $projectLaunch->day4_tvid,
            $projectLaunch->day5_tvid,
            $projectLaunch->day5_tvid,
            $projectLaunch->day6_tvid,
            $projectLaunch->day7_tvid,
            $projectLaunch->div_tvid,
            $projectLaunch->weekday_tvid,
            $projectLaunch->weekend_tvid,
        ]);

        $tpls = ProjectLaunchTpl::whereIn('tvid', $tplIds)->get();

        $tpldata = $this->collection($tpls, new ProjectLaunchTplTransformer());

        $data['day1template'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->day1_tvid);
        $data['day2template'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->day2_tvid);
        $data['day3template'] =$tpldata->getData()->firstWhere('tvid', $projectLaunch->day3_tvid);
        $data['day4template'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->day4_tvid);
        $data['day5template'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->day5_tvid);
        $data['day6template'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->day6_tvid);
        $data['day7template'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->day7_tvid);
        $data['divtemplate'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->div_tvid);
        $data['weekdaytemplate'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->weekday_tvid);
        $data['weekendtemplate'] = $tpldata->getData()->firstWhere('tvid', $projectLaunch->weekday_tvid);


        return $data;
    }

    public function includePoint(ProjectLaunch $projectLaunch)
    {
        return $this->item($projectLaunch->point, new PointTransformer());
    }

    public function includeProject(ProjectLaunch $projectLaunch)
    {
        return $this->item($projectLaunch->project, new ProjectTransformer());
    }


}