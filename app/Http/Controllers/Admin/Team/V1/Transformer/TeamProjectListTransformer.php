<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/27
 * Time: 下午6:46
 */

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class TeamProjectListTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['contract'];


    public function transform(TeamProject $teamProject)
    {
        $interaction_attribute_text = Collect(explode(',', $teamProject->interaction_attribute))->map(function ($value) {
            return (TeamProject::$interactionAttributeMapping)[$value] ?? "--";
        })->toArray();

        return [
            'id' => $teamProject->id,
            'copyright_attribute' => $teamProject->copyright_attribute == 1 ? '否' : '是',
            'copyright_project_id' => $teamProject->copyright_project_id,
            'copyright_project_name' => $teamProject->copyright_project ? $teamProject->copyright_project->project_name : '无',
            'project_name' => $teamProject->project_name,
            'belong' => $teamProject->belong,
            'applicant' => $teamProject->applicant,
            'applicant_name' => $teamProject->applicantUser ? $teamProject->applicantUser->name : '',
            'project_attribute' => (TeamProject::$projectAttributeMapping)[$teamProject->project_attribute] ?? '无',
            'hidol_attribute' => $teamProject->hidol_attribute == 1 ? '是' : '否',
            'individual_attribute' => (TeamProject::$individualAttributeMapping)[$teamProject->individual_attribute] ?? "未知",
            'contract_id' => $teamProject->contract_id,
            'contract_amount' => $teamProject->contract ? $teamProject->contract->amount : null,
            'interaction_attribute' => implode(',', $interaction_attribute_text),
            'link_attribute' => $teamProject->link_attribute == 1 ? '是' : '否',
            'h5_attribute' => (TeamProject::$h5AttributeMapping)[$teamProject->h5_attribute] ?? "无",
            'begin_date' => (string)$teamProject->begin_date,
            'online_date' => (string)$teamProject->online_date,
            'launch_date' => (string)$teamProject->launch_date,
            'art_innovate' => $teamProject->art_innovate,
            'dynamic_innovate' => $teamProject->dynamic_innovate,
            'interact_innovate' => $teamProject->interact_innovate,
            'remark' => $teamProject->remark,
            'status' => $teamProject->status,
            'type' => $teamProject->type == 1 ? '提前节目' : '正常节目',
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