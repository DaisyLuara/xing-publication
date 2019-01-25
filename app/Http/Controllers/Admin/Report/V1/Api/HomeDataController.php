<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/17
 * Time: 下午3:39
 */

namespace App\Http\Controllers\Admin\Report\V1\Api;


use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceLog;
use App\Http\Controllers\Admin\Report\V1\Request\ChartDataRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use DB;

class HomeDataController extends Controller
{
    public function chart(ChartDataRequest $request)
    {
        $xsFaceLogQuery = XsFaceLog::query();
        $faceCharacterCount = XsFaceCharacterCount::query();
        $xsFaceCountLog = XsFaceCountLog::query();
        switch ($request->id) {
            case 2:
                $data = $this->getTopPoints($request, $xsFaceLogQuery);
                break;
            case 3:
                $data = $this->getTopAttributes($request, $xsFaceCountLog);
                break;
            case 4:
                $data = $this->getAge($request, $xsFaceLogQuery, $faceCharacterCount);
                break;
            case 9:
                $data = $this->getActivePlayerByMonth($request);
                break;

            default:
                return null;

        }

        return response()->json($data);

    }

    /**
     * 点位排行榜
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getTopPoints(ChartDataRequest $request, Builder $query)
    {

        $this->handleQuery($request, $query, true, true);
        $table = $query->getModel()->getTable();
        $data = $query->selectRaw("sum($table.bnum+$table.gnum) AS total,sum($table.gnum) as female_count,sum($table.bnum) as male_count")
            ->groupBy("$table.oid")
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $output = [];
        $data->each(function ($item) use (&$output) {
            $output[] = [
                'count' => [
                    'male' => $item->male_count,
                    'female' => $item->female_count,
                    'total' => $item->total,
                ],
                'display_name' => $item->area_name . $item->market_name . ' ' . $item->point_name,
            ];
        });

        return $output;
    }

    /**
     * 点位排行榜(根据业态排行)
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getTopAttributes(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query, false);
        $table = $query->getModel()->getTable();
        $data = $query->selectRaw("sum(looknum) AS count,xs_attributes.name,xs_attributes.id as attribute_id")
            ->join('xs_point_attributes', 'xs_point_attributes.point_id', '=', "$table.oid")
            ->join('xs_attributes', 'xs_attributes.id', '=', 'xs_point_attributes.attribute_id')
            ->where('xs_attributes.parent_id', '=', 5)
            ->groupBy('xs_attributes.id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $output = [];
        $data->each(function ($item) use (&$output) {
            $output[] = [
                'count' => $item->count,
                'display_name' => $item->name,
                'attribute_id' => $item->attribute_id,
            ];
        });

        return $output;
    }

    /**
     * 年龄分布
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getAge(ChartDataRequest $request, Builder $query, Builder $query2)
    {
        //根据属性查询
        if ($request->attribute_id) {
            $this->handleQuery($request, $query2, false);
            return $this->getAgeGroupByAttribute($request->attribute_id, $query2);
        } else {
            $this->handleQuery($request, $query);
            return $this->getAgeGroupByGender($query);
        }

    }

    private function getAgeGroupByAttribute($attribute_id, Builder $query)
    {
        $table = $query->getModel()->getTable();
        $data = $query->selectRaw('sum(century10_bnum+century10_gnum) as century10,sum(century00_bnum+century00_gnum) as century00,sum(century90_bnum+century90_gnum) as century90,sum(century80_bnum+century80_gnum) as century80,sum(century70_bnum+century70_gnum) as century70')
            ->join('xs_point_attributes', 'xs_point_attributes.point_id', '=', "$table.oid")
            ->where('xs_point_attributes.attribute_id', '=', $attribute_id)
            ->first()->toArray();
        $output = [];
        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => $this->centuryMapping[$key],
            ];
        }
        return $output;

    }

    private function getAgeGroupByGender(Builder $query)
    {
        $data = $query->selectRaw('sum(age10b) as age10_male ,sum(age10g) as age10_female,sum(age18b) as age18_male,sum(age18g) as age18_female,sum(age30b) as age30_male,sum(age30g) as age30_female,sum(age40b) as age40_male,sum(age40g) as age40_female,sum(age60b) as age60_male,sum(age60g) as age60_female,sum(age61b) as age61_male,sum(age61g) as age61_female')
            ->first()->toArray();
        $output = [];


        $merge = [];
        foreach ($data as $key => $value) {
            $keys = explode('_', $key);
            $merge[$keys[0]][$keys[1]] = $value;
        }

        foreach ($merge as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => $this->ageMapping[$key],
            ];
        }
        return $output;
    }

    /**
     * 月活用户指数MAU
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getActivePlayerByMonth(ChartDataRequest $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $startMonth = (new Carbon($startDate))->format('Y-m');
        $endMonth = (new Carbon($endDate))->format('Y-m');
        $sql = DB::connection('ar')->table('xs_face_mau_market as fptmm')
            ->join('avr_official_market as aom', 'fptmm.marketid', '=', 'aom.marketid')
            ->whereRaw("date_format(fptmm.date,'%Y-%m') between '$startMonth' and '$endMonth'")
            ->groupBy('fptmm.marketid')
            ->orderBy('playernum', 'desc')
            ->selectRaw("aom.name as name,sum(active_player) as playernum");

        $marketnum = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->count();
        $data = $sql->limit(15)->get();
        $output['market_num'] = $marketnum;
        $output['data'] = [];
        foreach ($data as $item) {
            $output['data'][] = [
                'display_name' => $item->name,
                'count' => $item->playernum
            ];
        }

        return $output;
    }
}