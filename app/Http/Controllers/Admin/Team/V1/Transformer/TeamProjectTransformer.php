<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;

use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class TeamProjectTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['media'];

    public function transform(TeamProject $teamProject)
    {
        $member = $teamProject->member->toArray();
        $member = collect(array_column($member,'pivot'))->groupBy("type")->toArray();
        return [
            'id' => $teamProject->id,
            'project_name' => $teamProject->project_name,
            'belong' => $teamProject->belong,
            'applicant' => $teamProject->applicant,
            'applicant_name' => $teamProject->applicantUser->name,
            'project_attribute' => $teamProject->project_attribute,
            'link_attribute' => $teamProject->link_attribute,
            'h5_attribute' => $teamProject->h5_attribute,
            'xo_attribute' => $teamProject->xo_attribute,
            'begin_date' => $teamProject->begin_date,
            'online_date' => $teamProject->online_date,
            'launch_date' => $teamProject->launch_date,
            'art_innovate' => $teamProject->art_innovate,
            'dynamic_innovate' => $teamProject->dynamic_innovate,
            'interact_innovate' => $teamProject->interact_innovate,
            'remark' => $teamProject->remark,
            'status' => $teamProject->status,
            'type' => $teamProject->type,
            'member' =>$member,
        ];
    }

    public function includeMedia(TeamProject $teamProject)
    {
        if (!$teamProject->media) {
            return null;
        }
        return $this->item($teamProject->media, new MediaTransformer());
    }
}