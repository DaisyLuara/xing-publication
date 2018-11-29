<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:47
 */

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamRate;
use League\Fractal\TransformerAbstract;

class TeamRateTransformer extends TransformerAbstract
{
    public function transform(TeamRate $teamRate)
    {
        return [
            'id' => $teamRate->id,
            'interaction' => $teamRate->interaction,
            'originality' => $teamRate->originality,
            'h5_1' => $teamRate->h5_1,
            'h5_2' => $teamRate->h5_2,
            'animation' => $teamRate->animation,
            'plan' => $teamRate->plan,
            'tester' => $teamRate->tester,
            'operation' => $teamRate->operation
        ];
    }
}