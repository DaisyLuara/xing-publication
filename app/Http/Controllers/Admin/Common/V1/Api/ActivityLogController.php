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
        }

        if ($request->causer_name) {
            $causerName = $request->causer_name;
            $query->whereHas('causer', function ($query) use ($causerName) {
                $query->where('name', 'like', "%$causerName%");
            });
        }

        $activityLogs = $query->orderBy('id', 'desc')->paginate(10);

        return $this->response->paginator($activityLogs, new ActivityLogTransformer());
    }
}
