<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 下午2:42
 */

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamPersonRewardTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamPersonRewardController extends Controller
{
    /**
     * 个人奖金列表
     * @param Request $request
     * @param TeamPersonReward $teamPersonReward
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, TeamPersonReward $teamPersonReward)
    {
        $query = $teamPersonReward->query();
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereRaw("date_format(date,'%Y-%m-%d') between '$request->start_date' and '$request->end_date' ");
        }

        if ($request->has('start_get_date') && $request->has('end_get_date')) {
            $query->whereRaw("date_format(get_date,'%Y-%m-%d') between '$request->start_get_date' and '$request->end_get_date' ");
        }

        if ($request->has('name')) {
            $query->where('project_name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('main_type') && $request->main_type ) {
            $query->where('main_type', '=',  $request->main_type );
        }

        if ($request->has('type') && $request->type ) {
            $query->where('type', '=',  $request->type );
        }

        $user = $this->user();
        $teamPersonReward = $query->where('user_id', $user->id)->paginate(10);
        return $this->response()->paginator($teamPersonReward, new TeamPersonRewardTransformer());
    }

    public function totalReward(Request $request, TeamPersonReward $teamPersonReward)
    {
        $query = $teamPersonReward->query();

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