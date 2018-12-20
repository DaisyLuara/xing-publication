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

    protected $projectAttributeMapping = [
        '0' => '不计入',
        '1' => '基础条目',
        '2' => '通用节目',
        '3' => '定制节目*',
        '4' => '定制项目*',
        '5' => '简单条目',
        '6' => '通用节目',
        '7' => '项目',
    ];
    protected $h5AttributeMapping = [
        '1' => '基础模版',
        '2' => '复杂模版',
    ];

    protected $statusMapping = [
        '1' => '进行中',
        '2' => '测试已确认',
        '3' => '运营已确认',
        '4' => '主管已确认'
    ];
    protected $interactionAttributeMapping = [
        'interaction_api'=>'中间件属性',
        'interaction_linkage'=>'联动引擎属性'
    ];


    public function transform(TeamProject $teamProject)
    {
        $interaction_attribute_text = Collect(explode(',', $teamProject->interaction_attribute))->map(function ($value){
            return $this->interactionAttributeMapping[$value]??"--";
        })->toArray();

        return [
            'id' => $teamProject->id,
            'project_name' => $teamProject->project_name,
            'belong' => $teamProject->belong,
            'applicant' => $teamProject->applicant,
            'applicant_name' => $teamProject->applicantUser ? $teamProject->applicantUser->name : '',
            'project_attribute' => $this->projectAttributeMapping[$teamProject->project_attribute]??'无',
            'hidol_attribute' => $teamProject->hidol_attribute== 1 ? '是' : '否',
            'individual_attribute' => $teamProject->individual_attribute == 1 ? '是' : '否',
            'contract_id' => $teamProject->contract_id,
            'interaction_attribute' => implode(',',$interaction_attribute_text),
            'link_attribute' => $teamProject->link_attribute == 1 ? '是' : '否',
            'h5_attribute' => $this->h5AttributeMapping[$teamProject->h5_attribute]??"无",
            'xo_attribute' => $teamProject->xo_attribute == 1 ? '是' : '否',
            'begin_date' => (string)$teamProject->begin_date,
            'online_date' => (string)$teamProject->online_date,
            'launch_date' => (string)$teamProject->launch_date,
            'art_innovate' => $teamProject->art_innovate,
            'dynamic_innovate' => $teamProject->dynamic_innovate,
            'interact_innovate' => $teamProject->interact_innovate,
            'remark' => $teamProject->remark,
            'status' => $teamProject->status,
            'type' => $teamProject->type == 1 ? '提前节目' : '正常节目',
            'media' => $teamProject->media ? $teamProject->media->toArray() : [],
            'plan_media' => $teamProject->plan_media ? $teamProject->plan_media->toArray() : [],
            'animation_media' => $teamProject->animation_media ? $teamProject->plan_media->toArray() : [],
            'tester_media' => $teamProject->tester_media ? $teamProject->plan_media->toArray() : [],
            'operation_media' => $teamProject->operation_media ? $teamProject->plan_media->toArray() : [],
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