<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;

use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class TeamProjectTransformer extends TransformerAbstract
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
        '2' => '测试确认',
        '3' => '运营确认',
        '4' => '主管确认'
    ];

    public function transform(TeamProject $teamProject)
    {
        $member = $teamProject->member->toArray();
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
            'launch_date' => $teamProject->launch_date,
            'remark' => $teamProject->remark,
            'status' => $this->statusMapping[$teamProject->status],
            'member' => [
                'interaction' => array_column(array_filter($member, function ($arr) {
                    return $arr['pivot']['type'] == 'interaction';
                }), 'pivot'),
                'originality' => array_column(array_filter($member, function ($arr) {
                    return $arr['pivot']['type'] == 'originality';
                }), 'pivot'),
                'h5' => array_column(array_filter($member, function ($arr) {
                    return $arr['pivot']['type'] == 'h5';
                }), 'pivot'),
                'animation' => array_column(array_filter($member, function ($arr) {
                    return $arr['pivot']['type'] == 'animation';
                }), 'pivot'),
                'plan' => array_column(array_filter($member, function ($arr) {
                    return $arr['pivot']['type'] == 'plan';
                }), 'pivot'),
                'tester' => array_column(array_filter($member, function ($arr) {
                    return $arr['pivot']['type'] == 'tester';
                }), 'pivot'),
                'operation' => array_column(array_filter($member, function ($arr) {
                    return $arr['pivot']['type'] == 'operation';
                }), 'pivot'),
            ]
        ];
    }
}