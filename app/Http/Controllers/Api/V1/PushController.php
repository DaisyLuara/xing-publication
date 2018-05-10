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

        $query->join('avr_official', 'push.oid', '=', 'avr_official.oid')
            ->orderBy('areaid', 'desc')
            ->orderBy('marketid');

        if ($request->mb) {
            $mb = $request->mb;
            if ($mb == 'online') {
                $query->whereNotIn('push.oid', [-1, 30, 31,182,177,45,46,47,48,49,50,51,52])->where('state','=','0');
            } else if ($mb == 'dev') {
                $query->whereIn('push.oid',[30,31]);
            } else {
                $query->whereIn('state',[1,2,3,4,5,6,7]);
            }
        }

        $push = $query->orderBy('push.date', 'desc')
            ->paginate(10);
        return $this->response->paginator($push, new PushTransformer());
    }

}
