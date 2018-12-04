<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/27
 * Time: 下午6:46
 */

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class TeamProjectListTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['member'];

    protected $projectAttributeMapping = [
        '1' => '基础条目',
        '2' => '通用节目',
        '3' => '定制节目',
        '4' => '定制项目'
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

    public function transform(TeamProject $teamProject)
    {
        return [
            'id' => $teamProject->id,
            'project_name' => $teamProject->project_name,
            'belong' => $teamProject->belong,
            'applicant' => $teamProject->applicant,
            'applicant_name' => $teamProject->applicantUser->name,
            'project_attribute' => $this->projectAttributeMapping[$teamProject->project_attribute],
            'link_attribute' => $teamProject->link_attribute == 1 ? '是' : '否',
            'h5_attribute' => $this->h5AttributeMapping[$teamProject->h5_attribute],
            'xo_attribute' => $teamProject->xo_attribute == 1 ? '是' : '否',
            'begin_date' => $teamProject->begin_date,
            'online_date' => $teamProject->online_date,
            'launch_date' => date('Y-m-d', $teamProject->project->online),
            'remark' => $teamProject->remark,
            'status' => $this->statusMapping[$teamProject->status],
            'type' => $teamProject->type == 1 ? '提前节目' : '正常节目'
        ];
    }
}