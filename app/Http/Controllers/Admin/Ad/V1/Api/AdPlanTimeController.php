<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime;
use App\Http\Controllers\Admin\Ad\V1\Request\AdPlanTimeRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Dingo\Api\Http\Response;

class AdPlanTimeController extends Controller
{

    /**
     * 一般编辑
     * @param AdPlanTimeRequest $request
     * @param AdPlanTime $adPlanTime
     * @return Response
     */
    public function update(AdPlanTimeRequest $request, AdPlanTime $adPlanTime): Response
    {
        $updateParams = [
            'cdshow' => $request->get('cdshow'),
            'ktime' => $request->get('ktime'),
            'shm' => $request->get('shm') ? (int)Carbon::parse($request->get('shm'), 'UTC')->format('Hi') : 0,
            'ehm' => $request->get('ehm') ? (int)Carbon::parse($request->get('shm'), 'UTC')->format('Hi') : 0,
        ];

        if ($adPlanTime->ad_plan->type === AdPlan::TYPE_BID_SCREEN) {
            array_merge($updateParams, [
                'mode' => $request->get('mode') ?? 'fullscreen',
                'ori' => $request->get('ori') ?? 'center',
                'screen' => $request->get('screen') ?? 0,
            ]);
        }

        /** @var AdPlanTime $adPlanTime */
        $adPlanTime->fill($updateParams)->save();

        return $this->response->noContent();
    }
}
