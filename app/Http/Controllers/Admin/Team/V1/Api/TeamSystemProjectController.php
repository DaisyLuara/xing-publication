<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:07
 */

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamBonus;
use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
use App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject;
use App\Http\Controllers\Admin\Team\V1\Request\TeamSystemRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamPersonRewardTransformer;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamSystemProjectTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeamSystemProjectController extends Controller
{
    public function show(TeamSystemProject $teamSystemProject)
    {
        return $this->response()->item($teamSystemProject, new TeamSystemProjectTransformer());
    }

    public function index(Request $request, TeamSystemProject $teamSystemProject)
    {
        $query = $teamSystemProject->query();
        if ($request->has('name')) {
            $query->where('name', $request->name);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$request->start_date' and '$request->end_date'");
        }
        $teamSystemProject = $query->paginate(10);
        return $this->response()->paginator($teamSystemProject, new TeamSystemProjectTransformer());

    }

    public function store(TeamSystemRequest $request, TeamSystemProject $teamSystemProject)
    {
        $teamSystemProject->fill(array_merge($request->all(), ['status' => 1]))->save();
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function reject(Request $request, TeamSystemProject $teamSystemProject)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if (!$user->hasRole('legal-affairs-manager')) {
            abort(403, '无操作权限');
        }
        $teamSystemProject->update(array_merge($request->all(), ['status' => 3]));
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function distribute(Request $request, TeamSystemProject $teamSystemProject)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if (!$user->hasRole('legal-affairs-manager')) {
            abort(403, '无操作权限');
        }
        $teamSystemProject->status = 2;
        $teamSystemProject->update();
        //分配到个人账户
        TeamPersonReward::create([
            'user_id' => $teamSystemProject->applicant,
            'project_name' => $teamSystemProject->name,
            'belong' => 'system',
            'money' => $request->money,
            'date' => Carbon::now()->toDateTimeString()
        ]);
        return $this->response()->noContent()->setStatusCode(200);
    }

    /**
     * 平台总奖金
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function systemBonus(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $data = TeamBonus::query()->whereRaw("date_format(date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->selectRaw("sum(money) as total")
            ->first();

        $output = [
            'total_bonus' => $data->total ? round($data->total * 0.12, 2) : 0
        ];
        return response()->json($output);
    }

    /**
     * 已发放的平台奖金
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function distributionBonus(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $data = TeamPersonReward::query()->whereRaw("date_format(date,'%Y-%m-%d') between '$startDate' and '$endDate' and belong='system'")
            ->selectRaw("sum(money) as total")
            ->first();
        $output = [
            'distribution_bonus' => $data->total ? $data->total : 0
        ];
        return response()->json($output);

    }

    /**
     * 平台明细列表
     * @param Request $request
     * @param TeamPersonReward $teamPersonReward
     * @return \Dingo\Api\Http\Response
     */
    public function detailIndex(Request $request, TeamPersonReward $teamPersonReward)
    {
        $query = $teamPersonReward->query();

        if ($request->has('name')) {
            $query->where('project_name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereRaw("date_format(date,'%Y-%m-%d') between '$request->start_date' and '$request->end_date'");
        }

        $teamPersonReward = $query->whereRaw("belong='system'")->paginate(10);
        return $this->response()->paginator($teamPersonReward, new TeamPersonRewardTransformer());
    }

    /**
     * 新增分配
     * @param Request $request
     * @param TeamPersonReward $teamPersonReward
     * @return \Dingo\Api\Http\Response
     */
    public function detailStore(Request $request, TeamPersonReward $teamPersonReward)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('legal-affairs-manager')) {
            abort(403, '无操作权限');
        }
        $teamPersonReward->fill(array_merge($request->all(), ['belong' => 'system', 'date' => Carbon::now()->toDateTimeString()]))->save();
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function detailUpdate(Request $request, TeamPersonReward $teamPersonReward)
    {
        $user = $this->user();
        if (!$user->hasRole('legal-affairs-manager')) {
            abort(403, '无操作权限');
        }
        $teamPersonReward->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function detailShow(TeamPersonReward $teamPersonReward)
    {
        return $this->response()->item($teamPersonReward, new TeamPersonRewardTransformer());
    }
}