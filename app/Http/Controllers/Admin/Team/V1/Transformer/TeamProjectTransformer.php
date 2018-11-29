<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;

use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class TeamProjectTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['member'];

    public function transform(TeamProject $teamProject)
    {
        $member = $teamProject->member->toArray();
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
            'remark' => $teamProject->remark,
            'status' => $teamProject->status,
            'factor' => $teamProject->factor,
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