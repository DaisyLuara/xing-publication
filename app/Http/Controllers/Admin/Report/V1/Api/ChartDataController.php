<?php

namespace App\Http\Controllers\Admin\Report\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Request\ExportRequest;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCount;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceLog;
use App\Http\Controllers\Admin\Face\V1\Transformer\FaceCountTransformer;
use App\Http\Controllers\Admin\Report\V1\Request\ChartDataRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
        'century10' => '10后',
        'century00' => '00后',
        'century90' => '90后',
        'century80' => '80后',
        'century70' => '70前/后',
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
        'omo_outnum' => 'OMO有效跳转人数',
        'lovenum' => '扫码拉新会员注册总数',//最终转化
        'playertime' => '人均互动有效时长',//推算
    ];

    public function index(Request $request)
    {

        $query = XsFaceCountLog::query();
        $table = $query->getModel()->getTable();
        $this->handleQuery($request, $query);

        $startClientdate = strtotime($request->start_date) * 1000;
        $endClientdate = strtotime($request->end_date) * 1000;
        $faceCount = $query->selectRaw("max($table.clientdate) as max_date,min($table.clientdate) as min_date,$table.id as id
        ,sum(looknum) as looknum,sum(playernum7) as playernum7,sum(playernum) as playernum,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum
        ,avr_official.name as point_name,avr_official_market.name as market_name,avr_official_area.name as area_name,xs_face_count_log.date as created_at")
            ->selectRaw("(SELECT GROUP_CONCAT(DISTINCT (ar_product_list.name)) FROM xs_face_count_log AS fcl2 INNER JOIN ar_product_list ON ar_product_list.versionname = fcl2.belong WHERE fcl2.oid = $table.oid AND fcl2.clientdate  BETWEEN '$startClientdate' AND '$endClientdate' GROUP BY fcl2.oid) as projects ")
            //->where("$table.fclid", '>', 0)
            ->groupBy("$table.oid")
            ->orderBy('avr_official_area.areaid', 'desc')
            ->orderBy('avr_official_market.marketid', 'desc')
            ->orderBy('avr_official.oid', 'desc')
            ->paginate(5);

        return $this->response->paginator($faceCount, new FaceCountTransformer());
    }


    public function chart(ChartDataRequest $request)
    {
        $xsFaceLogQuery = XsFaceLog::query();
        $faceCharacterCount = XsFaceCharacterCount::query();
        $xsFaceCountLog = XsFaceCountLog::query();
        switch ($request->id) {
            case 1:
                $data = $this->getLookNumber($request, $xsFaceLogQuery);
                break;
            case 2:
                $data = $this->getTopPoints($request, $xsFaceLogQuery);
                break;
            case 3:
                $data = $this->getTopAttributes($request, $xsFaceCountLog);
                break;
            case 4:
                $data = $this->getAge($request, $xsFaceLogQuery, $faceCharacterCount);
                break;
            case 5:
                $data = $this->getGender($request, $xsFaceLogQuery);
                break;
            case 6:
                $data = $this->getTotal($request, $xsFaceCountLog);
                break;
            case 7:
                $data = $this->getTotalByDate($request, $xsFaceCountLog);
                break;
            case 8:
                $data = $this->getCharacterByTime($request, $faceCharacterCount);
                break;
            case 9:
                $data = $this->getActivePlayerByMonth($request);
                break;
            case 10:
                $data = $this->getFunnelChart($request, $xsFaceCountLog);
                break;
            case 11:
                $data = $this->getTopProjects($request, $xsFaceCountLog);
                break;
            case 12:
                $data = $this->getProjectCharacter($request, $faceCharacterCount);
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
            ->first()->toArray();
    }

    private function getGenderGroupByAge(string $gender, Builder $query)
    {
        $suffix = $gender == 'male' ? 'b' : 'g';
        return $query->selectRaw("sum(age10$suffix) as age10,sum(age18$suffix) as age18,sum(age30$suffix) as age30,sum(age40$suffix) as age40,sum(age60$suffix) as age60,sum(age61$suffix) as age61")
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
     * 获取总的 围观人数 玩家人数 会员人数
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array
     */
    private function getTotal(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        $data = $query->selectRaw("sum(looknum) AS looknum,sum(playernum7)as playernum7,sum(playernum) AS playernum,sum(omo_outnum) as omo_outnum,sum(lovenum)  AS lovenum")
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
        $data = $query->selectRaw("date_format(xs_face_count_log.date, '$format') as display_name")
            ->groupBy('display_name')
            ->get();

        if ($days <= 31) {
            $data = $data->toArray();
            $output = [];
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            while ($start_date <= $end_date) {
                if (arrayMultiSearch($start_date, $data)) {
                    $item = array_filter($data, function ($arr) use ($start_date) {
                        return $arr['display_name'] == $start_date;
                    });
                    sort($item);
                    $item = $item[0];
                    $output[] = [
                        'display_name' => $item['display_name'],
                        'looknum' => $item['looknum'],
                        'playernum7' => $item['playernum7'],
                        'playernum' => $item['playernum'],
                        'omo_outnum' => $item['omo_outnum'],
                        'lovenum' => $item['lovenum'],
                    ];
                } else {
                    $output[] = [
                        'display_name' => $start_date,
                        'looknum' => 0,
                        'playernum7' => 0,
                        'playernum' => 0,
                        'omo_outnum' => 0,
                        'lovenum' => 0,
                    ];
                }
                $start_date = (new Carbon($start_date))->addDay(1)->toDateString();
            }
            return $output;
        } else {
            return $data;
        }
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
    public function getCharacterByTime(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        $data = $query->groupBy('time')
            ->selectRaw("time,sum(century10_bnum+century10_gnum) as century10, sum(century00_bnum + century00_gnum) as century00,sum(century90_bnum + century90_gnum) as century90,sum(century80_bnum + century80_gnum) as century80,sum(century70_bnum + century70_gnum) as century70")
            ->selectRaw("sum(century10_gnum+century00_gnum+century90_gnum+century80_gnum+century70_gnum) as gnum,sum(century10_bnum+century10_gnum+century00_gnum+century00_bnum+century90_gnum+century90_bnum+century80_gnum+century80_bnum+century70_gnum+century70_bnum) as totalnum")
            ->get();

        $times = ['10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', '24:00'];
        $displayTime = [
            '00:00-10:00',
            '10:00-12:00',
            '12:00-14:00',
            '14:00-16:00',
            '16:00-18:00',
            '18:00-20:00',
            '20:00-22:00',
            '22:00-24:00',
        ];
        $output = [];
        foreach ($displayTime as $key => $value) {
            $output[] = [
                'display_name' => $value,
                'rate' => 0,
                'count' =>
                    [
                        'century10' => 0,
                        'century00' => 0,
                        'century90' => 0,
                        'century80' => 0,
                        'century70' => 0,
                    ]
            ];
        }
        foreach ($data as $item) {
            $flag = 1;
            $i = 0;
            while ($flag) {
                if ($item->time == $times[$i]) {
                    $output[$i]['count'] = [
                        'century10' => $item->century10,
                        'century00' => $item->century00,
                        'century90' => $item->century90,
                        'century80' => $item->century80,
                        'century70' => $item->century70,
                    ];
                    $output[$i]['rate'] = $item->totalnum == 0 ? 0 : strval(round($item->gnum / $item->totalnum, 3) * 100);
                    $flag = 0;
                } else {
                    $i++;
                }
            }
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

    public function getFunnelChart(ChartDataRequest $request, Builder $query)
    {
        $startClientdate = strtotime($request->start_date) * 1000;
        $endClientdate = strtotime($request->end_date) * 1000;

        $this->handleQuery($request, $query);

        $data = $query->selectRaw("sum(looknum) as looknum ,sum(playernum7) as playernum7,sum(playernum) as playernum ,sum(omo_outnum) as omo_outnum,sum(lovenum) as lovenum")
            ->first()->toArray();

        $allData = XsFaceCountLog::query()
            ->selectRaw("sum(looknum) as looknum ,sum(playernum7) as playernum7,sum(playernum) as playernum ,sum(omo_outnum) as omo_outnum,sum(lovenum) as lovenum")
            ->whereRaw("clientdate between '$startClientdate' and '$endClientdate' and belong='all'")
            ->first();

        $query = XsFaceCountLog::query();
        $this->handleQuery($request, $query);
        $count = $query->selectRaw("count(distinct avr_official.oid) as oid_count,count(distinct avr_official.marketid) as market_count")
            ->first();

        $output = [];
        foreach ($data as $key => $value) {
            $output['data'][] = [
                'count' => $value,
                'display_name' => $this->totalMapping[$key],
                'index' => $key,
            ];
        }
        $output['rate']['total_rate'] = [
            [
                'count' => $allData->looknum ? strval(round($allData->playernum7 / $allData->looknum, 3) * 100) : 0,
                'display_name' => 'CPF转化率',
            ],
            [
                'count' => $allData->looknum ? strval(round($allData->playernum / $allData->looknum, 3) * 100) : 0,
                'display_name' => 'CPR转化率',
            ],
            [
                'count' => $allData->looknum ? strval(round($allData->omo_outnum / $allData->looknum, 3) * 100) : 0,
                'display_name' => 'CPA转化率',
            ],
            [
                'count' => $allData->looknum ? strval(round($allData->lovenum / $allData->looknum, 3) * 100) : 0,
                'display_name' => 'CPL转化率',
            ],
        ];
        $output['rate']['rate'] = [
            [
                'count' => $data['looknum'] ? strval(round($data['playernum7'] / $data['looknum'], 3) * 100) : 0,
                'display_name' => 'CPF转化率',
            ],
            [
                'count' => $data['looknum'] ? strval(round($data['playernum'] / $data['looknum'], 3) * 100) : 0,
                'display_name' => 'CPR转化率',
            ],
            [
                'count' => $data['looknum'] ? strval(round($data['omo_outnum'] / $data['looknum'], 3) * 100) : 0,
                'display_name' => 'CPA转化率',
            ],
            [
                'count' => $data['looknum'] ? strval(round($data['lovenum'] / $data['looknum'], 3) * 100) : 0,
                'display_name' => 'CPL转化率',
            ],
        ];

        $output['oid_count'] = $count->oid_count;
        $output['market_count'] = $count->market_count;
        $output['day'] = (new Carbon($request->start_date))->diffInDays((new Carbon($request->end_date))) + 1;

        return $output;
    }

    public function getTopProjects(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query, false);
        $table = $query->getModel()->getTable();
        $data = $query->selectRaw("ar_product_list.name as product_name,belong,round(sum(looknum)/count($table.date),0) as looknum,round(sum(playernum7)/count($table.date),0) as playernum7,round(sum(playernum)/count($table.date),0) as playernum,round(sum(omo_outnum)/count($table.date),0) as omo_outnum, round(sum(lovenum)/count($table.date),0) as lovenum")
            ->groupBy('belong')
            ->orderBy(DB::raw(" looknum"), 'desc')
            ->limit(10)
            ->get();

        $output = [];
        foreach ($data as $item) {
            $output[] = [
                'display_name' => $item->product_name,
                'index' => $item->belong,
                'count' => [
                    'looknum' => $item->looknum,
                    'playernum7' => $item->playernum7,
                    'playernum' => $item->playernum,
                    'omo_outnum' => $item->omo_outnum,
                    'lovenum' => $item->lovenum
                ]
            ];
        };
        return array_reverse($output);
    }

    public function getProjectCharacter(ChartDataRequest $request, Builder $query)
    {
        $belong = $request->belong;
        $this->handleQuery($request, $query, false);
        $table = $query->getModel()->getTable();
        $data = $query->selectRaw("sum(century10_bnum+century10_gnum) as century10,sum(century00_bnum+century00_gnum) as century00,sum(century90_bnum+century90_gnum) as century90,sum(century80_bnum+century80_gnum) as century80,sum(century70_bnum+century70_gnum) as century70")
            ->whereRaw("$table.belong='$belong'")
            ->get();
        $output = [];
        foreach ($data as $item) {
            $output = [
                'century10' => $item->century10,
                'century00' => $item->century00,
                'century90' => $item->century90,
                'century80' => $item->century80,
                'century70' => $item->century70,
            ];
        }
        return $output;
    }

    private function handleQuery(Request $request, Builder $query, $selectByAlias = true, bool $selectPoint = false)
    {
        $user = $this->user();
        $table = $query->getModel()->getTable();
        $arUserZ = $request->home_page ? 0 : getArUserZ($user, $request);
        handPointQuery($request, $query, $arUserZ, $selectPoint);

        //节目搜索-注意业务逻辑
        if ($selectByAlias) {
            $alias = $request->alias ? $request->alias : 'all';
            $query->where("$table.belong", '=', $alias);
        } else {
            $query->join('ar_product_list', 'ar_product_list.versionname', '=', "$table.belong");
        }

    }

    public function export(ExportRequest $request)
    {
        return excelExport($request);
    }

}
