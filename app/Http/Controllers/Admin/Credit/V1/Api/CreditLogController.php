<?php

namespace App\Http\Controllers\Admin\Credit\V1\Api;

use App\Http\Controllers\Admin\Credit\V1\Models\CreditLog;
use App\Http\Controllers\Admin\Credit\V1\Transformer\CreditLogTransformer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Dingo\Api\Http\Response;
use Illuminate\Support\Carbon;

class CreditLogController extends Controller
{

    /**
     * 场地主分值log记录 -- 默认只显示当天的
     * @param Request $request
     * @param CreditLog $creditLog
     * @return Response
     */
    public function index(Request $request, CreditLog $creditLog): Response
    {

        //默认只显示当天的
        $start_date = Carbon::today('PRC')->toDateString();
        $end_date = Carbon::tomorrow('PRC')->toDateString();
        if ($request->get('start_date') && $request->get('end_date')) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
        }

        $query = $creditLog->query()
            ->whereHas('ar_member', static function ($q) {
                $q->whereHas('ar_user', static function ($q) {
                    $q->where('role_id', '=', 11);
                });
            })
            ->whereBetween('date', [$start_date, $end_date]);

        if ($request->get('ccid')) {
            $query->where('ccid', '=', $request->get('ccid'));
        }

        $creditLogs = $query->orderByDesc('date')->paginate(10);
        return $this->response->paginator($creditLogs, new CreditLogTransformer());
    }

}
