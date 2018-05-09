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

            $q->orderBy('areaid', 'desc')->orderBy('marketid', 'desc');

        });
        $push = $query->orderBy('date', 'desc')
            ->paginate(10);
        return $this->response->paginator($push, new PushTransformer());
    }
}
