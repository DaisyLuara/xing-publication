<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project', 'divtemplate','day1template','day2template','day3template',
        'day4template','day5template','day6template','day7template','weekdaytemplate','weekendtemplate'];

    public function transform(ProjectLaunch $projectLaunch)
    {
        return [
            'id' => $projectLaunch->tvoid,
            'start_date' => date('Y-m-d H:i:s', $projectLaunch->sdate),
            'end_date' => date('Y-m-d H:i:s', $projectLaunch->edate),
            'created_at' => $projectLaunch->date,
            'updated_at' => formatClientDate($projectLaunch->clientdate),
        ];
    }

    public function includePoint(ProjectLaunch $projectLaunch)
    {
        return $this->item($projectLaunch->point, new PointTransformer());
    }

    public function includeProject(ProjectLaunch $projectLaunch)
    {
        return $this->item($projectLaunch->project, new ProjectTransformer());
    }

    public function includeDivtemplate(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->divtemplate) {
            return $this->item($projectLaunch->divtemplate, new ProjectLaunchTplTransformer());
        }
    }

    public function includeDay1template(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->day1template) {
            return $this->item($projectLaunch->day1template, new ProjectLaunchTplTransformer());
        }
    }

    public function includeDay2template(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->day2template) {
            return $this->item($projectLaunch->day2template, new ProjectLaunchTplTransformer());
        }
    }

    public function includeDay3template(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->day3template) {
            return $this->item($projectLaunch->day3template, new ProjectLaunchTplTransformer());
        }
    }

    public function includeDay4template(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->day4template) {
            return $this->item($projectLaunch->day4template, new ProjectLaunchTplTransformer());
        }
    }

    public function includeDay5template(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->day5template) {
            return $this->item($projectLaunch->day5template, new ProjectLaunchTplTransformer());
        }
    }

    public function includeDay6template(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->day6template) {
            return $this->item($projectLaunch->day6template, new ProjectLaunchTplTransformer());
        }
    }

    public function includeDay7template(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->day7template) {
            return $this->item($projectLaunch->day7template, new ProjectLaunchTplTransformer());
        }
    }

    public function includeWeekdaytemplate(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->weekdaytemplate) {
            return $this->item($projectLaunch->weekdaytemplate, new ProjectLaunchTplTransformer());
        }
    }

    public function includeWeekendtemplate(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->weekendtemplate) {
            return $this->item($projectLaunch->weekendtemplate, new ProjectLaunchTplTransformer());
        }
    }

}