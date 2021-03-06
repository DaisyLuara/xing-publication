<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class TeamProjectTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['contract'];

    public function transform(TeamProject $teamProject)
    {
        $member = $teamProject->member->toArray();
        $member = collect(array_column($member, 'pivot'))->groupBy("type")->toArray();

        return [
            'id' => $teamProject->id,
            'copyright_attribute' => $teamProject->copyright_attribute,
            'copyright_project_id' => $teamProject->copyright_project_id,
            'copyright_project_name' => $teamProject->copyright_project ? $teamProject->copyright_project->project_name : '无',
            'project_name' => $teamProject->project_name,
            'belong' => $teamProject->belong,
            'applicant' => $teamProject->applicant,
            'applicant_name' => $teamProject->applicantUser ? $teamProject->applicantUser->name : '',
            'project_attribute' => (int)$teamProject->project_attribute,
            'hidol_attribute' => $teamProject->hidol_attribute,
            'individual_attribute' => $teamProject->individual_attribute,
            'contract_id' => $teamProject->contract_id,
            'interaction_attribute' => $teamProject->interaction_attribute ? explode(',', $teamProject->interaction_attribute) : [],
            'link_attribute' => $teamProject->link_attribute,
            'h5_attribute' => $teamProject->h5_attribute,
            'begin_date' => $teamProject->begin_date,
            'online_date' => $teamProject->online_date,
            'launch_date' => $teamProject->launch_date,
            'art_innovate' => $teamProject->art_innovate,
            'dynamic_innovate' => $teamProject->dynamic_innovate,
            'interact_innovate' => $teamProject->interact_innovate,
            'remark' => $teamProject->remark,
            'status' => $teamProject->status,
            'type' => $teamProject->type,
            'member' => $member,
            'media' => $teamProject->media ? $teamProject->media->toArray() : [],
            'animation_media' => $teamProject->animation_media ? $teamProject->animation_media->toArray() : null,
            'tester_media' => $teamProject->tester_media ? $teamProject->tester_media->toArray() : null,
            'test_remark' => $teamProject->test_remark,
        ];
    }

    public function includeContract(TeamProject $teamProject)
    {
        $contract = $teamProject->contract;
        if ($contract) {
            return $this->item($contract, new ContractTransformer());
        }
        return null;
    }

}