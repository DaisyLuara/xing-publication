<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Transformer\ActivityLogTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\ActivityLogRequest;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index(ActivityLogRequest $request, Activity $activity)
    {

        $user = $this->user();
        $query = $activity->query();
        if (!$user->isAdmin()) {
            $query->where('causer_id', '=', $user->id);
        } elseif ($request->has('causer_id')) {
            $query->where('causer_id', '=', $request->causer_id);
        }

        if ($request->has('log_name')) {
            $query->where('log_name', $request->log_name);
        }


        $activityLogs = $query->orderBy('id', 'desc')->paginate(10);

        return $this->response->paginator($activityLogs, new ActivityLogTransformer());
    }
}
