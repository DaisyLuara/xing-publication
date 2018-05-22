<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\Api\V1\ActivityLogRequest;
use App\Transformers\ActivityLogTransformer;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(ActivityLogRequest $request, Activity $activity)
    {

        $user = $this->user();
        $query = $activity->query();
        if (!$user->isAdmin()) {
            $query->whereHas('causer', function ($query) use ($user) {
                $query->where('causer_id', '=', $user->id);
            });
        }

        /**
         * @todo 优化
         */
        if ($request->causer_name) {
            $causerName = $request->causer_name;
            $query->whereHas('causer', function ($query) use ($causerName) {
                $query->where('name', 'like', "%$causerName%");
            });
        }

        $activityLogs = $query->paginate(10);

        return $this->response->paginator($activityLogs, new ActivityLogTransformer());
    }
}
