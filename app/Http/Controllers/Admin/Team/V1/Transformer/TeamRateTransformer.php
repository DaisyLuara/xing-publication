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
            'originality' => $teamRate->originality,
            'plan' => $teamRate->plan,
            'animation' => $teamRate->animation,
            'animation_hidol' => $teamRate->animation_hidol,
            'hidol_patent' => $teamRate->hidol_patent,
            'interaction_api' => $teamRate->interaction_api,
            'interaction_linkage' => $teamRate->interaction_linkage,
            'backend_docking' => $teamRate->backend_docking,
            'h5_1' => $teamRate->h5_1,
            'h5_2' => $teamRate->h5_2,
            'tester' => $teamRate->tester,
            'tester_quality' => $teamRate->tester_quality,
            'operation' => $teamRate->operation,
            'operation_quality' => $teamRate->operation_quality,
        ];
    }
}