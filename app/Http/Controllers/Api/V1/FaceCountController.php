<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FaceCount;
use Illuminate\Http\Request;
use App\Transformers\FaceCountTransformer;
use Carbon\Carbon;

class FaceCountController extends Controller
{
    public function index(Request $request, FaceCount $faceCount)
    {
        $query = $faceCount->query();

        $date_start = $request->has('start_date') ? $request->strat_date : Carbon::now()->addDays(-7);
        $date_end = $request->has('end_date') ? $request->end_date : Carbon::now();

        //默认取最近7天数据
        $query->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end'");

        //关联节目
        $belong = $request->has('belong') ? $request->belong : 'all';
        $query->where('belong', '=', $belong);

        if ($request->has('oid')) {
            $query->where('oid', '=', $request->oid);
        }

        if ($request->has('uid')) {
            $query->where('uid', '=', $request->uid);
        }
        $faceCounts = $query->selectRaw('sum(looknum) as looknum ,sum(playernum) as playernum ,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum')->get();

        return $this->response->item($faceCounts, new FaceCountTransformer());
    }
}
