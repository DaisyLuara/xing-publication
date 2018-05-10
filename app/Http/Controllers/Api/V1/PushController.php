<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Push;
use App\Transformers\PushTransformer;
use Illuminate\Http\Request;

class PushController extends Controller
{
    public function index(Request $request, Push $push)
    {
        $query = $push->query();
        $query->whereHas('point', function ($q) use ($request) {
            $user = $this->user();
            $arUserId = getArUserID($user, $request);
            if ($arUserId) {
                $q->whereHas('arUsers', function ($q) use ($arUserId) {
                    $q->where('admin_staff.uid', '=', $arUserId);
                });
            }

            if ($request->has('project_id')) {
                $q->whereHas('projects', function ($q) use ($request) {
                    $q->where('id', '=', $request->project_id);
                });
            }

            if ($request->has('point_id')) {
                $q->where('oid', '=', $request->point_id);
            }

        });

        if ($request->machine_status) {
            $machine_status = $request->machine_status;
            if ($machine_status == 'online') {
                $query->whereNotIn('oid', [30, 31, 16, 177])->where('state', '=', '0');
            } else if ($machine_status == 'cp') {
                $query->whereNotIn('oid', [30, 31, 16, 177])->where('state', '=', -1);
            } elseif ($machine_status == 'tmp') {
                $query->whereNotIn('oid', [30, 31, 16, 177])->where('state', '>', 0);
            } elseif ($machine_status == 'dev') {
                $query->whereIn('oid', [30, 31, 16, 177]);
            }
        }

        $push = $query->where('push.oid', '>', 0)
            ->whereNotIn('push.alias', ['star', 'shop', 'agent'])
            ->orderBy('oid','desc')
            ->orderBy('clientdate', 'desc')
            ->paginate(10);

        return $this->response->paginator($push, new PushTransformer());
    }

}
