<?php

namespace App\Http\Controllers\Admin\Activity\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Transformer\ActivityParticipantsTransformer;
use App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityParticipantsController extends Controller
{

    public function index(Request $request, ActivityParticipant $activityParticipant)
    {

        $query = $activityParticipant->query();
        $user = $this->user();

        //玩法配置
        if ($request->has('aid')) {
            $query->where('aid', $request->aid);
        }

        //状态
        if ($request->has('pass')) {
            $query->where('aid', $request->aid);
        }


        $activityParticipants = $query->orderByDesc('auid')->paginate(10);
        return $this->response->paginator($activityParticipants, new ActivityParticipantsTransformer());
    }


}
