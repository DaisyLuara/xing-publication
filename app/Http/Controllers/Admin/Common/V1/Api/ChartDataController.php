<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Face\V1\Transformer\FaceCountTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\ChartDataRequest;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCount;
use App\Http\Controllers\Admin\Face\V1\Models\FaceLog;
use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ChartDataController extends Controller
{
    protected $ageMapping = [
        'age10' => '0-10岁',
        'age18' => '11-18岁',
        'age30' => '19-30岁',
        'age40' => '31-40岁',
        'age60' => '41-60岁',
        'age61' => '60岁以上',
    ];

    protected $centuryMapping = [
        'century00' => '00后',
        'century90' => '90后',
        'century80' => '80后',
        'century70' => '70后',
    ];

    protected $genderMapping = [
        'male' => '男',
        'female' => '女',
    ];

    protected $totalMapping = [
        'exposurenum' => '曝光人数',//围观人数(todo)
        'looknum' => '大屏围观参与人数',
        'playernum7' => '大屏活跃玩家人数', //互动超过7秒(todo)
        'playernum' => '大屏铁杆玩家人数',//游戏玩结束
        'lovenum' => '扫码拉新会员注册总数',//最终转化
        'palyertime' => '人均互动有效时长',//推算
    ];

    public function index(Request $request)
    {

        $query = FaceCount::query();
        $table = $query->getModel()->getTable();
        $this->handleQuery($request, $query);

        $faceCount = $query->selectRaw("max($table.clientdate) as max_date,min($table.clientdate) as min_date,fclid as id,sum(looknum) as looknum,sum(playernum) as playernum,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum,avr_official.name as point_name,avr_official_market.name as market_name,avr_official_area.name as area_name,face_count_log.date as created_at")
            ->selectRaw("(SELECT GROUP_CONCAT(DISTINCT (ar_product_list.name)) FROM face_count_log AS fcl2 INNER JOIN ar_product_list ON ar_product_list.versionname = fcl2.belong WHERE fcl2.oid = $table.oid AND date_format(fcl2.date, '%Y-%m-%d') BETWEEN '$request->start_date' AND '$request->end_date' GROUP BY fcl2.oid) as projects ")
            ->where("$table.fclid", '>', 0)
            ->groupBy("$table.oid")
            ->orderBy('avr_official_area.areaid', 'desc')
            ->orderBy('avr_official_market.marketid', 'desc')
            ->orderBy('avr_official.oid', 'desc')
            ->paginate(5);

        return $this->response->paginator($faceCount, new FaceCountTransformer());
    }


    public function chart(ChartDataRequest $request)
    {
        $faceLogQuery = FaceLog::query();
        $faceCountQuery = FaceCount::query();
        switch ($request->id) {
            case 1:
                $data = $this->getLookNumber($request, $faceLogQuery);
                break;
            case 2:
                $data = $this->getTopPoints($request, $faceLogQuery);
                break;
            case 3:
                $data = $this->getTopProjects($request, $faceCountQuery);
                break;
            case 4:
                $data = $this->getAge($request, $faceLogQuery);
                break;
            case 5:
                $data = $this->getGender($request, $faceLogQuery);
                break;
            case 6:
                $data = $this->getTotal($request, $faceCountQuery);
                break;
            case 7:
                $data = $this->getTotalByDate($request, $faceCountQuery);
                break;
            case 8:
                $data = $this->getCharacterByTime($request);
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
     * 围观人数 分段显示
     * @param $request
     * @param $query
     * @return array
     */
    private function getLookNumber($request, $query)
    {
        $startDate = (new Carbon($request->start_date))->timestamp;
        $endDate = (new Carbon($request->end_date))->timestamp;
        $days = ($endDate - $startDate) / 24 / 60 / 60;

        $this->handleQuery($request, $query);
        $table = $query->getModel()->getTable();
        $query->where("$table.type", '=', 'looker');

        if ($days) {
            $format = $days <= 31 ? '%Y-%m-%d' : '%Y-%m';
            return $query->selectRaw("sum(allnum) AS count,date_format(face_log.date, '$format') AS display_name")
                ->groupBy('display_name')
                ->get();
        }

        $data = $query->selectRaw("sum(t10) AS t10,sum(t11) AS t11,sum(t12) AS t12,sum(t13) AS t13,sum(t14) AS t14,sum(t15) AS t15,sum(t16) AS t16,sum(t17) AS t17,sum(t18) AS t18,sum(t19) AS t19,sum(t20) AS t20,sum(t21) AS t21,sum(t22) AS t22")
            ->first()
            ->toArray();
        $output = [];
        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => str_replace('t', '', $key) . ":00",
            ];
        }
        return $output;
    }

    /**
     * 年龄分布
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getAge(ChartDataRequest $request, Builder $query)
    {
        //根据属性查询
        if ($request->attribute_id) {
            $this->handleQuery($request, $query, false);
            return $this->getAgeGroupByAttribute($request->attribute_id, $query);
        } else {
            $this->handleQuery($request, $query);
            return $this->getAgeGroupByGender($query);
        }

    }

    private function getAgeGroupByAttribute($attribute_id, Builder $query)
    {
        $table = $query->getModel()->getTable();
        $data = $query->selectRaw('sum(age10b+age10g+age18b+age18g) as century00,sum(age30b+age30g) as century90,sum(age40b+age40g) as century80,sum(age60b+age60g) as century70')
            ->join('xs_point_attributes', 'xs_point_attributes.point_id', '=', "$table.oid")
            ->where('xs_point_attributes.attribute_id', '=', $attribute_id)
            ->where('face_log.type', '=', 'looker')
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
            ->where('face_log.type', '=', 'looker')
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
     * 性别数据
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getGender(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        if ($request->gender) {
            $mapping = $this->ageMapping;
            $data = $this->getGenderGroupByAge($request->gender, $query);
        } else {
            $mapping = $this->genderMapping;

            $data = $this->getGenderAll($query);
        }

        $output = [];
        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => $mapping[$key],
            ];
        }
        return $output;
    }

    private function getGenderAll(Builder $query)
    {
        return $query->selectRaw("sum(gnum) as female,sum(bnum) as male")
            ->where('face_log.type', '=', 'looker')
            ->first()->toArray();
    }

    private function getGenderGroupByAge(string $gender, Builder $query)
    {
        $suffix = $gender == 'male' ? 'b' : 'g';
        return $query->selectRaw("sum(age10$suffix) as age10,sum(age18$suffix) as age18,sum(age30$suffix) as age30,sum(age40$suffix) as age40,sum(age60$suffix) as age60,sum(age61$suffix) as age61")
            ->where('face_log.type', '=', 'looker')
            ->first()->toArray();

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
        $data = $query->selectRaw("sum($table.allnum) AS total,sum($table.gnum) as female_count,sum($table.bnum) as male_count")
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
                'display_name' => $item->area_name . $item->market_name . ' ' . $item->name,
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
    private function getTopProjects(ChartDataRequest $request, Builder $query)
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
     * 获取总的 围观人数 玩家人数 会员人数
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array
     */
    private function getTotal(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        $data = $query->join('face_people_time_active_player', function ($join) {
            $join->on('face_count_log.oid', '=', 'face_people_time_active_player.oid')
                ->on('face_count_log.belong', '=', 'face_people_time_active_player.belong')
                ->whereRaw("date_format(face_count_log.date,'%Y-%m-%d')=date_format(face_people_time_active_player.date,'%Y-%m-%d')");
        }, null, null, 'left')
            ->selectRaw("sum(looknum) AS looknum,sum(playernum7)as playernum7,sum(playernum) AS playernum,sum(lovenum)  AS lovenum")
            ->first()->toArray();
        $output = [];
        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => $this->totalMapping[$key],
                'index' => $key,
            ];
        }
        return $output;

    }

    /**
     * 获取单一指标的分天数据
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getTotalByDate(ChartDataRequest $request, Builder $query)
    {
        $startDate = (new Carbon($request->start_date))->timestamp;
        $endDate = (new Carbon($request->end_date))->timestamp;
        $days = ($endDate - $startDate) / 24 / 60 / 60;
        $format = $days <= 31 ? '%Y-%m-%d' : '%Y-%m';

        $this->handleQuery($request, $query);
        return $query->join('face_people_time_active_player', function ($join) {
            $join->on('face_count_log.oid', '=', 'face_people_time_active_player.oid')
                ->on('face_count_log.belong', '=', 'face_people_time_active_player.belong')
                ->whereRaw("date_format(face_count_log.date,'%Y-%m-%d')=date_format(face_people_time_active_player.date,'%Y-%m-%d')");
        }, null, null, 'left')
            ->selectRaw("date_format(face_count_log . date, '$format') as display_name")
            ->groupBy('display_name')
            ->get();

    }

    public function getAllPeopleByPoint($arUserID = 0)
    {
        $data = DB::connection('ar')->table('face_count_log')
            ->selectRaw("sum(looknum) AS looknum,
                         sum(playernum) AS playernum,
                         sum(lovenum)  AS lovenum,
                         max(face_count_log . clientdate) as max,
                         min(face_count_log . clientdate) as min,
                         face_count_log . oid")
            ->where('belong', '=', 'all')
            ->groupBy('face_count_log.oid')
            ->orderBy('looknum', 'desc')
            ->limit(100)
            ->get();

        $output = 0;
        $data->each(function ($item) use (&$output) {
            $day = ceil(($item->max - $item->min) / 1000 / 24 / 3600);
            $output += $day;
        });

        return $output;
    }

    /**
     * 获取时间段与人群特征
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getCharacterByTime(ChartDataRequest $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $startClientDate = strtotime($startDate) * 1000;
        $endClientDate = strtotime($endDate) * 1000;

        $time1 = " when time >'00:00' and time <='10:00' then '10:00'";
        $time2 = " when time >'10:00' and time <='12:00' then '12:00'";
        $time3 = " when time >'12:00' and time <='14:00' then '14:00'";
        $time4 = " when time >'14:00' and time <='16:00' then '16:00'";
        $time5 = " when time >'16:00' and time <='18:00' then '18:00'";
        $time6 = " when time >'18:00' and time <='20:00' then '20:00'";
        $time7 = " when time >'20:00' and time <='22:00' then '22:00'";
        $time8 = " when time >'22:00' or time ='00:00' then '24:00'";
        $timeSql = $time1 . $time2 . $time3 . $time4 . $time5 . $time6 . $time7 . $time8;

        $data = DB::connection('ar')->table('face_collect_character')
            ->whereRaw("clientdate between '$startClientDate' and '$endClientDate'")
            ->where('belong', '=', 'all')
            ->where('century', '<>', '0')
            ->whereNotIn('oid', ['16', '19', '30', '31', '335', '334', '329', '328', '327'])
            ->groupBy('times')
            ->groupBy('century')
            ->selectRaw("case" . $timeSql . " else 0 end as times,century,sum(looknum) as count")
            ->get();

        $count = [];
        for ($i = 0; $i < 8; $i++) {
            $count[$i] = [
                'century00' => 0,
                'century90' => 0,
                'century80' => 0,
                'century70' => 0,
            ];
        }
        $mapping = [
            '00' => 'century00',
            '90' => 'century90',
            '80' => 'century80',
            '70' => 'century70'
        ];
        foreach ($data as $item) {
            if ($item->times == "10:00") {
                $count[0][$mapping[$item->century]] = $item->count;
            }
            if ($item->times == "12:00") {
                $count[1][$mapping[$item->century]] = $item->count;
            }
            if ($item->times == "14:00") {
                $count[2][$mapping[$item->century]] = $item->count;
            }
            if ($item->times == "16:00") {
                $count[3][$mapping[$item->century]] = $item->count;
            }
            if ($item->times == "18:00") {
                $count[4][$mapping[$item->century]] = $item->count;
            }
            if ($item->times == "20:00") {
                $count[5][$mapping[$item->century]] = $item->count;
            }
            if ($item->times == "22:00") {
                $count[6][$mapping[$item->century]] = $item->count;
            }
            if ($item->times == "24:00") {
                $count[7][$mapping[$item->century]] = $item->count;
            }
        }
        $sql = DB::connection('ar')->table('face_collect_character')
            ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and belong = 'all' and century <> 0 and oid not in ('16', '19', '30', '31', '335', '334', '329', '328', '327')")
            ->groupBy('times')
            ->groupBy('gender')
            ->selectRaw("case" . $timeSql . " else 0 end as times,gender,sum(looknum) as count");
        $genderData = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->groupBy('times')
            ->selectRaw("  times,sum(if(gender = 'Male', count, 0))as malenum,sum(if(gender = 'Female', count, 0)) as femalenum")
            ->get();
        $rate = [
            '10:00' => 0,
            '12:00' => 0,
            '14:00' => 0,
            '16:00' => 0,
            '18:00' => 0,
            '20:00' => 0,
            '22:00' => 0,
            '24:00' => 0,
        ];
        foreach ($genderData as $item) {
            $rate[$item->times] = round($item->femalenum / ($item->malenum + $item->femalenum), 3) * 100 . '%';
        }
        $times = ['10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', '24:00'];
        $output = [];
        for ($i = 0; $i < count($times); $i++) {
            $output[] = [
                'count' => $count[$i],
                'rate' => $rate[$times[$i]],
                'display_name' => $times[$i]
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
        $sql = DB::connection('ar')->table('face_people_time_mau_market as fptmm')
            ->join('avr_official_market as aom', 'fptmm.marketid', '=', 'aom.marketid')
            ->whereRaw("date_format(fptmm.date,'%Y-%m') between '$startMonth' and '$endMonth'")
            ->groupBy('fptmm.marketid')
            ->orderBy('playernum', 'desc')
            ->selectRaw("aom.name as name,sum(active_player) as playernum");

        $marketnum = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->count();
        $data = $sql->limit(10)->get();
        $output['market_num'] = $marketnum;
        $output['data'] = [];
        foreach ($data as $item) {
            $output['data'][] = [
                'display_name' => $item->name,
                'count' => $item->playernum
            ];
        }

        return $output;
//        $data = DB::connection('ar')->table('face_people_time_mau_market')
//            ->selectRaw("playernum,date_format(date, '%Y-%m') as month")
//            ->orderBy('month')
//            ->get();
//        $output = [];
//        foreach ($data as $item) {
//            $output[] = [
//                "month" => $item->month,
//                "playernum" => round($item->playernum / 10000, 1)
//            ];
//        }
//        if (count($output) != 0) {
//            $output[0]['rate'] = 0;
//        }
//        for ($i = 1; $i < count($output); $i++) {
//            $output[$i]["rate"] = round(($output[$i]['playernum'] - $output[$i - 1]['playernum']) / $output[$i - 1]['playernum'], 2);
//        }
//        return $output;
    }

    private function handleQuery(Request $request, Builder $query, $selectByAlias = true, bool $selectPoint = false)
    {
        $user = $this->user();
        $table = $query->getModel()->getTable();
        $arUserID = $request->home_page ? 0 : getArUserID($user, $request);
        handPointQuery($request, $query, $arUserID, $selectPoint);

        //节目搜索-注意业务逻辑
        if ($selectByAlias) {
            $alias = $request->alias ? $request->alias : 'all';
            $query->where("$table.belong", '=', $alias);
        } else {
            $query->join('ar_product_list', 'ar_product_list.versionname', '=', "$table.belong");
        }

    }

}
