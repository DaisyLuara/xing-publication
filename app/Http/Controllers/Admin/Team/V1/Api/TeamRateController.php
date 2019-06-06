<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:34
 */

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamRate;
use App\Http\Controllers\Admin\Team\V1\Request\TeamRateRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamRateTransformer;
use App\Http\Controllers\Controller;

class TeamRateController extends Controller
{
    public function show(TeamRate $teamRate)
    {
        return $this->response()->item($teamRate, new TeamRateTransformer());
    }

    public function index(TeamRate $teamRate)
    {
        $query = $teamRate->query();
        $teamRate = $query->paginate(10);
        return $this->response()->paginator($teamRate, new TeamRateTransformer());
    }

//    public function store(TeamRateRequest $request, TeamRate $teamRate)
////    {
////        $teamRate->fill($request->all())->save();
////        return $this->response()->noContent()->setStatusCode(201);
////    }

    public function update(TeamRateRequest $request, TeamRate $teamRate)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $teamRate->update($request->all());

        activity('update_team_rate')
            ->causedBy($user)
            ->performedOn($teamRate)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('更新节目智造比例');

        return $this->response()->noContent()->setStatusCode(200);
    }
}