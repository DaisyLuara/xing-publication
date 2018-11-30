<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:07
 */

namespace App\Http\Controllers\Admin\Team\V1\Api;


use App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject;
use App\Http\Controllers\Admin\Team\V1\Request\TeamSystemRequest;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamSystemProjectTransformer;
use App\Http\Controllers\Controller;
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
        if ($query->has('status')) {
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

    public function systemBonus(){

    }
}