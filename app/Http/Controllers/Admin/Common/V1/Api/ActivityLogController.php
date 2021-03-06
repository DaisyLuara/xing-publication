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

        /** @var User $user */
        $user = $this->user();
        $query = $activity->query();

        if ($request->has('log_name')) {
            $query->where('log_name', $request->get('log_name'));
        }

        if ($request->get('type') === 'customer') {
            $query->where('causer_type', Customer::class);

            if ($request->get('causer_id')) {
                $query->where('causer_id', '=', $request->get('causer_id'));
            }

            if (!$user->canSeeOperateLog()) {
                $customer_ids = Customer::query()->whereHas('company', static function ($q) use ($user) {
                    $q->where('bd_user_id', '=', $user->id);
                })->pluck('id')->toArray();

                $query->whereIn('causer_id', $customer_ids);
            }
        }

        if ($request->get('type') === 'user') {
            $query->where('causer_type', User::class);
            if (!$user->canSeeOperateLog()) {
                $query->where('causer_id', '=', $user->id);
            } else if ($request->has('causer_id')) {
                $query->where('causer_id', '=', $request->get('causer_id'));
            }
        }

        if ($request->get('subject_type')) {

            $query->whereRaw("subject_type = '" . str_replace('\\', '\\\\', $request->get('subject_type')) . "'");

            if ($request->get('subject_id')) {
                $query->where('subject_id', '=', $request->get('subject_id'));
            }
        }

        $activityLogs = $query->orderBy('id', 'desc')->paginate(10);

        return $this->response->paginator($activityLogs, new ActivityLogTransformer());
    }
}
