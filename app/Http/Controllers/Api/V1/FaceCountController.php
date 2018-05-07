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

        $date_start = $request->has('start_date') ? $request->start_date : Carbon::now()->addDays(-7);
        $date_end = $request->has('end_date') ? $request->end_date : Carbon::now();

        $belong = $request->has('belong') ? $request->belong : 'all';

        $uid = 0;
        if ($this->user()->isAdmin() && $request->has('ar_user_id')) {
            $uid = $request->ar_user_id;
        } else if (!$this->user()->isAdmin()) {
            $uid = $this->user()->ar_user_id;
        }

        $query = $faceCount->query();
        if ($uid) {
            $query->whereHas('apo', function ($q) use ($uid) {
                $q->where('uid', '=', $uid);
            });
        }

        if ($request->has('oid')) {
            $query->where('oid', '=', $request->oid);
        }

        $faceCount = $query->where('belong', '=', $belong)
            ->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end'")
            ->selectRaw('sum(looknum) as looknum ,sum(playernum) as playernum ,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum')
            ->get();

        return $this->response->item($faceCount, new FaceCountTransformer());
    }

    //分天统计
    public function detail(Request $request, FaceCount $faceCount)
    {
        $date_start = $request->has('start_date') ? $request->start_date : Carbon::now()->addDays(-7);
        $date_end = $request->has('end_date') ? $request->end_date : Carbon::now();

        $type = $request->has('type') ? $request->type : 'looknum';

        $query = $faceCount->query();

        $uid = 0;
        if (!$this->user()->isAdmin()) {
            $uid = $this->user()->ar_user_id;
        }
        if ($uid) {
            $query->whereHas('apo', function ($q) use ($uid) {
                $q->where('uid', '=', $uid);
            });
        }
        if ($request->has('oid')) {
            $query->where('oid', '=', $request->oid);
        }
        $faceCount = $query->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end'")
            ->where('belong', '=', 'all')
            ->selectRaw("date_format(date,'%Y-%m-%d') as date,sum({$type}) as count")
            ->groupBy(DB::raw("date_format(date,'%Y-%m-%d')"))
            ->get();

        return $this->response->array($faceCount);
    }

    //年龄性别分布
    public function ageAndGenderDetail(Request $request, FaceLog $faceLog)
    {

        $date_start = $request->has('start_date') ? $request->start_date : Carbon::now()->addDays(-7);
        $date_end = $request->has('end_date') ? $request->end_date : Carbon::now();

        $query = $faceLog->query();

        $uid = 0;
        if (!$this->user()->isAdmin()) {
            $uid = $this->user()->ar_user_id;
        }
        if ($uid) {
            $query->whereHas('apo', function ($q) use ($uid) {
                $q->where('uid', '=', $uid);
            });
        }
        if ($request->has('oid')) {
            $query->where('oid', '=', $request->oid);
        }

        $faceLog=$query->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end'")
            ->where('type', '=', 'looker')
            ->where('belong', '=', 'all')
            ->selectRaw('sum(gnum) as gnum,sum(bnum) as bnum,
            sum(age10b+age10g) as age10,sum(age18b+age18g) as age18,sum(age30b+age30g) as age30,
            sum(age40b+age40g) as age40,sum(age60b+age60g) as age60,sum(age61b+age61g) as age61')
            ->first();

        return $this->response->item($faceLog, new FaceLogTransformer());
    }

}
