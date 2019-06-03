<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Transformer\ActivityLogTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\ActivityLogRequest;
use App\Models\Customer;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index(ActivityLogRequest $request, Activity $activity)
    {

        $user = $this->user();
        $query = $activity->query();

        if ($request->has('log_name')) {
            $query->where('log_name', $request->get('log_name'));
        }

        if ($request->get('type') === 'customer') {
            if (!$user->isAdmin()){
                abort(403,'您无权查看商户操作记录');
            }

            $query->where('causer_type', Customer::class);

            if ($request->get('causer_id')) {
                $query->where('causer_id', '=', $request->get('causer_id'));
            }
        }

        if ($request->get('type') === 'user') {
            $query->where('causer_type', User::class);
            if (!$user->isAdmin()) {
                $query->where('causer_id', '=', $user->id);
            } else if ($request->has('causer_id')) {
                $query->where('causer_id', '=', $request->get('causer_id'));
            }
        }

        if ($request->get('subject_type')) {
            $query->where('subject_type', 'like', '%' . $request->get('subject_type') . '%');
            if ($request->get('subject_id')) {
                $query->where('subject_id', '=', $request->get('subject_id'));
            }
        }

        $activityLogs = $query->orderBy('id', 'desc')->paginate(10);

        return $this->response->paginator($activityLogs, new ActivityLogTransformer());
    }
}
