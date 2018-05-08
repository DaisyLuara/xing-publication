<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FaceCount;
use App\Models\FaceLog;
use App\Transformers\FaceCountTransformer;
use App\Transformers\FaceLogTransformer;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class FaceCountController extends Controller
{
    public function index(Request $request, FaceCount $faceCount)
    {
        $query = $this->queryInit($request, $faceCount->query());
        $default = $this->getDefaultParams($request);

        $faceCount = $query->where('belong', '=', $default['alias'])
            ->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '" . $default['start_date'] . "' AND '" . $default['end_date'] . "'")
            ->selectRaw('sum(looknum) as looknum ,sum(playernum) as playernum ,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum')
            ->first();

        return $this->response->item($faceCount, new FaceCountTransformer());
    }

    public function detail(Request $request, FaceCount $faceCount)
    {


        $query = $this->queryInit($request, $faceCount->query());
        $default = $this->getDefaultParams($request);

        $faceCount = $query->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '" . $default['start_date'] . "' AND '" . $default['end_date'] . "'")
            ->where('belong', '=', $default['alias'])
            ->selectRaw("date_format(date,'%Y-%m-%d') as date,sum(" . $default['type'] . ") as count")
            ->groupBy(DB::raw("date_format(date,'%Y-%m-%d')"))
            ->get();

        return $this->response->array($faceCount);
    }

    private function queryInit($request, $query)
    {
        $user = $this->user();
        $arUserId = getArUserID($user, $request);
        if ($arUserId) {
            $query->whereHas('pointArUser', function ($q) use ($arUserId) {
                $q->where('uid', '=', $arUserId);
            });
        }

        if ($request->has('oid')) {
            $query->where('oid', '=', $request->oid);
        }

        return $query;

    }

    public function getDefaultParams($request)
    {
        $start_date = $request->has('start_date') ? (new Carbon($request->start_date))->toDateString() : Carbon::now()->addDays(-7)->toDateString();
        $end_date = $request->has('end_date') ? (new Carbon($request->end_date))->toDateString() : Carbon::now()->toDateString();
        $alias = $request->has('alias') ? $request->alias : 'all';
        $type = $request->has('type') ? $request->type : 'looknum';

        return compact('start_date', 'end_date', 'alias', 'type');
    }
}
