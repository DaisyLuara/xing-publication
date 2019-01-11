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
            'type' => (array_merge(TeamPersonReward::$typeMapping,TeamPersonReward::$typeCopyrightMapping))[$teamPersonReward->type]??'--',
            'experience_money' => $teamPersonReward->experience_money ? round($teamPersonReward->experience_money, 6) : 0,
            'total' => $teamPersonReward->total ? round($teamPersonReward->total, 6) : 0,
            'date' => (new Carbon($teamPersonReward->date))->toDateString(),
            'get_date' => (new Carbon($teamPersonReward->date))->toDateString()
        ];
    }
}