<?php

namespace App\Http\Controllers\Admin\Device\V1\Api;

use App\Http\Controllers\Admin\Device\V1\Transformer\PushTransformer;
use App\Http\Controllers\Admin\Device\V1\Models\Push;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushController extends Controller
{
    public function index(Request $request, Push $push)
    {
        $query = $push->query();

        $user = $this->user();
        $arUserID = $request->home_page ? 0 : getArUserID($user, $request);
        handPointQuery($request, $query, $arUserID, true);

        if ($request->machine_status) {
            $machine_status = $request->machine_status;
            if ($machine_status == 'online') {
                $query->whereNotIn('push.oid', [30, 31, 16, 177])->where('state', '=', '0');
            } else if ($machine_status == 'cp') {
                $query->whereNotIn('push.oid', [30, 31, 16, 177])->where('state', '=', -1);
            } elseif ($machine_status == 'tmp') {
                $query->whereNotIn('push.oid', [30, 31, 16, 177])->where('state', '>', 0);
            } elseif ($machine_status == 'dev') {
                $query->whereIn('push.oid', [30, 31, 16, 177]);
            }
        }

        if ($request->alias) {
            $query->where('alias', '=', $request->alias);
        }

        $query->selectRaw('push.*,ar_product_list.icon as product_img');
        $pushes = $query->join('ar_product_list', 'ar_product_list.versionname', '=', "push.alias")
            ->where('push.oid', '>', 0)
            ->where('avr_official.visiable', '=', 1)
            ->whereNotIn('push.alias', ['star', 'shop', 'agent'])
            ->orderBy('avr_official.areaid', 'desc')
            ->orderBy('avr_official.marketid', 'desc')
            ->orderBy('push.clientdate', 'desc')
            ->paginate(10);

        return $this->response->paginator($pushes, new PushTransformer());
    }

}
