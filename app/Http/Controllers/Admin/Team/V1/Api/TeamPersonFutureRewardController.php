<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 下午2:42
 */

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamPersonFutureRewardTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamPersonFutureRewardController extends Controller
{


    /**
     * 个人冻结奖金列表
     * @param Request $request
     * @param TeamPersonFutureReward $teamPersonFutureReward
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, TeamPersonFutureReward $teamPersonFutureReward)
    {
        $query = $teamPersonFutureReward->query();

        if ($request->has('alias')) {
            $query->where('belong', $request->alias);
        }

        if ($request->has('start_date') && $request->has('end_date')
            && $request->start_date && $request->end_date) {
            $query->whereRaw("date_format(date,'%Y-%m-%d') between '$request->start_date' and '$request->end_date' ");
        }

        if ($request->has('start_get_date') && $request->has('end_get_date')) {
            $query->whereRaw("date_format(get_date,'%Y-%m-%d') between '$request->start_get_date' and '$request->end_get_date' ");
        }

        $user = $this->user();
        $teamPersonFuturePersonRewards = $query->where('user_id', $user->id)
            ->groupBy("belong")
            ->paginate(10);

        return $this->response()->paginator($teamPersonFuturePersonRewards, new TeamPersonFutureRewardTransformer($request));
    }

    /**
     * 个人冻结总绩效
     * @param Request $request
     * @param TeamPersonFutureReward $teamFuturePersonReward
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalReward(Request $request, TeamPersonFutureReward $teamFuturePersonReward)
    {
        $query = $teamFuturePersonReward->query();

        $query->where('status', '=', 0);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereRaw("date_format(date,'%Y-%m-%d') between '$request->start_date' and '$request->end_date' ");
        }

        if ($request->has('start_get_date') && $request->has('end_get_date')) {
            $query->whereRaw("date_format(get_date,'%Y-%m-%d') between '$request->start_get_date' and '$request->end_get_date' ");
        }

        if ($request->has('name')) {
            $query->where("project_name", 'like', '%' . $request->name . '%');
        }
        $user = $this->user();

        $data = $query->where('user_id', $user->id)->selectRaw("sum(total) as total")->first();
        $output = [
            'total_reward' => $data->total ? round($data->total, 2) : 0
        ];

        return response()->json($output);
    }
}