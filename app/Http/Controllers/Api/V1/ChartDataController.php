<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Location\Models\Point;
use App\Http\Requests\Api\V1\ChartDataRequest;
use App\Models\FaceCount;
use Carbon\Carbon;
use DB;

class ChartDataController extends Controller
{
    public function index(ChartDataRequest $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $data = null;
        switch ($request->id) {
            case 1:
                $data = $this->getLookPeople($startDate, $endDate);
                break;
            case 2:
                $data = $this->getTopPoints($startDate, $endDate);
                break;
            case 3:
                $data = $this->getTopProjects($startDate, $endDate);
                break;
            default:
                return null;

        }

        return response()->json($data);

    }

    private function getLookPeople($startDate, $endDate)
    {
        $dt = new Carbon($startDate);
        $preStartDate = $dt->previous()->toDateString();

        $dt = new Carbon($endDate);
        $preEndDate = $dt->previous()->toDateString();


        $groupByDay = $startDate != $endDate;

        if ($groupByDay) {
            $new = $this->getDataByDay($startDate, $endDate);
            $old = $this->getDataByDay($preStartDate, $preEndDate);
            return array_merge($new, $old);
        }
        $new = $this->getDataByHour($startDate, $endDate);
        $old = $this->getDataByHour($preStartDate, $preEndDate);
        return array_merge($new, $old);

    }

    private function getDataByHour($startDate, $endDate)
    {
        $data = DB::connection('ar')->table('face_log')
            ->selectRaw("sum(t10) AS t10,
                             sum(t11) AS t11,
                             sum(t12) AS t12,
                             sum(t13) AS t13,
                             sum(t14) AS t14,
                             sum(t15) AS t15,
                             sum(t16) AS t16,
                             sum(t17) AS t17,
                             sum(t18) AS t18,
                             sum(t19) AS t19,
                             sum(t20) AS t20,
                             sum(t21) AS t21,
                             sum(t22) AS t22")
            ->whereRaw("date_format(date, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate' ")
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->first();
        $output = [];
        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => str_replace('t', '', $key) . ":00",
            ];
        }
        return $output;
    }

    private function getDataByDay($startDate, $endDate)
    {
        $data = DB::connection('ar')->table('face_log')
            ->selectRaw("sum(allnum) AS count,date_format(face_log.date, '%Y-%m-%d') AS day")
            ->whereRaw("date_format(date, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate' ")
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('day')
            ->get();
        $output = [];
        $data->each(function ($item) use (&$output) {
            $output[] = [
                'count' => $item->count,
                'display_name' => $item->day
            ];
        });

        return $output;
    }


    private function getTopPoints($startDate, $endDate)
    {
        $data = DB::connection('ar')->table('face_count_log')
            ->join('avr_official', 'avr_official.oid', '=', 'face_count_log.oid')
            ->join('avr_official_market', 'avr_official_market.marketid', '=', 'avr_official.marketid')
            ->where('belong', '<>', 'all')
            ->selectRaw("sum(looknum) AS count,avr_official.name,avr_official_market.name as market_name")
            ->whereRaw("date_format(face_count_log.date, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate' ")
            ->whereNotIn('face_count_log.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('belong')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $output = [];
        $data->each(function ($item) use (&$output) {
            $output[] = [
                'count' => $item->count,
                'display_name' => $item->market_name . ' ' . $item->name
            ];
        });

        return $output;
    }

    private function getTopProjects($startDate, $endDate)
    {
        $data = DB::connection('ar')->table('face_count_log')
            ->join('ar_product_list', 'ar_product_list.versionname', '=', 'face_count_log.belong')
            ->where('belong', '<>', 'all')
            ->selectRaw("sum(looknum) AS count,ar_product_list.name")
            ->whereRaw("date_format(face_count_log.date, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate' ")
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('belong')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $output = [];
        $data->each(function ($item) use (&$output) {
            $output[] = [
                'count' => $item->count,
                'display_name' => $item->name
            ];
        });

        return $output;
    }
}
