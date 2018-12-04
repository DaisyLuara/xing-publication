<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 下午1:59
 */

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TeamPersonRewardTransformer extends TransformerAbstract
{
    public function transform(TeamPersonReward $teamPersonReward)
    {
        return [
            'id' => $teamPersonReward->id,
            'user_id' => $teamPersonReward->user_id,
            'user_name' => $teamPersonReward->user->name,
            'project_name' => $teamPersonReward->project_name,
            'experience_money' => $teamPersonReward->experience_money ? $teamPersonReward->experience_money : 0,
            'xo_money' => $teamPersonReward->xo_money ? $teamPersonReward->xo_money : 0,
            'link_money' => $teamPersonReward->link_money ? $teamPersonReward->link_money : 0,
            'system_money' => $teamPersonReward->system_money ? $teamPersonReward->system_money : 0,
            'total' => $teamPersonReward->total ? $teamPersonReward->total : 0,
            'date' => (new Carbon($teamPersonReward->date))->toDateString()
        ];
    }
}