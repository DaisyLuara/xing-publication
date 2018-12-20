<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TeamPersonFutureRewardTransformer extends TransformerAbstract
{
    protected $typeMapping = [
        'interaction' => '交互技术',
        'originality' => '节目创意',
        'h5' => 'H5开发',
        'animation' => '设计动画',
        'plan' => '节目统筹',
        'tester' => '节目测试',
        'operation' => '平台运营',
        'system' => '平台奖',
        'tester_quality'=>'节目测试-责任绩效',
        'operation_quality'=>'平台运营-责任绩效',
    ];

    protected $statusMapping = [
        0 => '冻结中',
        1 => '已发放',
        -1 => '已扣除',
    ];

    public function transform(TeamPersonFutureReward $teamPersonFutureReward)
    {
        return [
            'id' => $teamPersonFutureReward->id,
            'user_id' => $teamPersonFutureReward->user_id,
            'user_name' => $teamPersonFutureReward->user->name,
            'project_name' => $teamPersonFutureReward->project_name,
            'type' => $this->typeMapping[$teamPersonFutureReward->type]??'--',
            'experience_money' => $teamPersonFutureReward->experience_money ? round($teamPersonFutureReward->experience_money, 2) : 0,
            'xo_money' => $teamPersonFutureReward->xo_money ? round($teamPersonFutureReward->xo_money, 2) : 0,
            'link_money' => $teamPersonFutureReward->link_money ? round($teamPersonFutureReward->link_money, 2) : 0,
            'system_money' => $teamPersonFutureReward->system_money ? round($teamPersonFutureReward->system_money, 2) : 0,
            'total' => $teamPersonFutureReward->total ? round($teamPersonFutureReward->total, 2) : 0,
            'date' => (new Carbon($teamPersonFutureReward->date))->toDateString(),
            'get_date'=>Carbon::parse($teamPersonFutureReward->get_date)->timezone('PRC')->toDateString(),
            'status' => $this->statusMapping[$teamPersonFutureReward->status]??'未知',
            'team_project_id'=>$teamPersonFutureReward->team_project_id,
        ];
    }
}