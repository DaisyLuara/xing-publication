<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/16
 * Time: 上午10:07
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;


use App\Http\Controllers\Admin\Common\V1\Request\ChartDataRequest;
use App\Http\Controllers\Admin\Common\V1\Transformer\ChartDataTimesTransformer;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCharacterCountTimes;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceLogTimes;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChartDataTimesController extends Controller
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
        'looktimes' => '围观人次',//围观人数
        'playtimes7' => '7″fCPE',
        'playtimes15' => '15″fCPE',
        'playtimes21' => '21″fCPE',
        'outnum' => 'fCPR(二维码生成数)',
        'omo_scannum' => 'fCPA(扫码跳转次数)',
        'lovetimes' => 'fCPL(拉新会员数)',
//        '' => 'fCPS(到店核销次数)'
    ];

    protected $rateMapping = [
        'looktimes' => 'CPM',
        'playtimes7' => '7″fCPE',
        'playtimes15' => '15″fCPE',
        'playtimes21' => '21″fCPE',
        'outnum' => 'fCPR',
        'omo_scannum' => 'fCPA',
        'lovetimes' => 'fCPL',
    ];

    public function index(Request $request)
    {

        $query = XsFaceCountLog::query();
        $table = $query->getModel()->getTable();
        $this->handleQuery($request, $query);

        $faceCount = $query->selectRaw("max($table.clientdate) as max_date,min($table.clientdate) as min_date,$table.id as id,sum(looktimes) as looktimes,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(outnum) as outnum,sum(omo_scannum) as omo_scannum,sum(lovetimes) as lovetimes,avr_official.name as point_name,avr_official_market.name as market_name,avr_official_area.name as area_name,xs_face_count_log.date as created_at")
            ->selectRaw("(SELECT GROUP_CONCAT(DISTINCT (ar_product_list.name)) FROM xs_face_count_log AS fcl2 INNER JOIN ar_product_list ON ar_product_list.versionname = fcl2.belong WHERE fcl2.oid = $table.oid AND date_format(fcl2.date, '%Y-%m-%d') BETWEEN '$request->start_date' AND '$request->end_date' GROUP BY fcl2.oid) as projects ")
            //->where("$table.fclid", '>', 0)
            ->groupBy("$table.oid")
            ->orderBy('avr_official_area.areaid', 'desc')
            ->orderBy('avr_official_market.marketid', 'desc')
            ->orderBy('avr_official.oid', 'desc')
            ->paginate(5);

        return $this->response()->paginator($faceCount, new ChartDataTimesTransformer());
    }

    public function chart(ChartDataRequest $request)
    {
        $xsFaceCountLog = XsFaceCountLog::query();
        $xsFaceLogTimes = XsFaceLogTimes::query();
        $xsFaceCharacterCountTimes = XsFaceCharacterCountTimes::query();
        switch ($request->id) {
            case 1:
                $data = $this->getTotal($request, $xsFaceCountLog);
                break;
            case 2:
                $data = $this->getTotalByDate($request, $xsFaceCountLog);
                break;
            case 3:
                $data = $this->getPermeabilityByGender($request, $xsFaceLogTimes);
                break;
            case 4:
                $data = $this->getPermeabilityByAge($request, $xsFaceLogTimes);
                break;
            case 5:
                $data = $this->getCharacterByTime($request, $xsFaceCharacterCountTimes);
                break;
            case 6:
                $data = $this->getTopProjects($request, $xsFaceCountLog);
                break;
            case 7:
                $data = $this->getProjectCharacter($request, $xsFaceCharacterCountTimes);
                break;
            default:
                return null;

        }
        return response()->json($data);
    }

    /**
     * 获取总的数据
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array
     */
    private function getTotal(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        $data = $query->selectRaw("sum(looktimes) as looktimes,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(outnum) as outnum ,sum(omo_scannum) as omo_scannum,sum(lovetimes) as lovetimes")
            ->first()->toArray();
        $output = [];

        foreach ($data as $key => $value) {
            $output[] = [
                'number' => [
                    'count' => $value,
                    'display_name' => $this->totalMapping[$key],
                    'index' => $key,
                ],
                'rate' => [
                    'display_name' => $this->rateMapping[$key],
                    'value' => $key == 'looktimes' ? 0 : ($data['looktimes'] == 0 ? 0 : (round($value / $data['looktimes'], 3) * 100) . '%'),
                ]
            ];
        }
        return $output;

    }

    /**
     * 获取分天数据
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
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
                if ($this->array_multi_search($start_date, $data)) {
                    $item = array_filter($data, function ($arr) use ($start_date) {
                        return $arr['display_name'] == $start_date;
                    });
                    sort($item);
                    $item = $item[0];
                    $output[] = [
                        'display_name' => $item['display_name'],
                        'looktimes' => $item['looktimes'],
                        'playtimes7' => $item['playtimes7'],
                        'playtimes15' => $item['playtimes15'],
                        'playtimes21' => $item['playtimes21'],
                        'outnum' => $item['outnum'],
                        'omo_scannum' => $item['omo_scannum'],
                        'lovetimes' => $item['lovetimes'],
                        'looktimes_rate' => 0,
                        'playtimes7_rate' => $item['looktimes'] == 0 ? 0 : (round($item['playtimes7'] / $item['looktimes'], 3) * 100) . '%',
                        'playtimes15_rate' => $item['looktimes'] == 0 ? 0 : (round($item['playtimes15'] / $item['looktimes'], 3) * 100) . '%',
                        'playtimes21_rate' => $item['looktimes'] == 0 ? 0 : (round($item['playtimes21'] / $item['looktimes'], 3) * 100) . '%',
                        'outnum_rate' => $item['looktimes'] == 0 ? 0 : (round($item['outnum'] / $item['looktimes'], 3) * 100) . '%',
                        'omo_scannum_rate' => $item['looktimes'] == 0 ? 0 : (round($item['omo_scannum'] / $item['looktimes'], 3) * 100) . '%',
                        'lovetimes_rate' => $item['looktimes'] == 0 ? 0 : (round($item['lovetimes'] / $item['looktimes'], 3) * 100) . '%',

                    ];
                } else {
                    $output[] = [
                        'display_name' => $start_date,
                        'looktimes' => 0,
                        'playtimes7' => 0,
                        'playtimes15' => 0,
                        'playtimes21' => 0,
                        'outnum' => 0,
                        'omo_scannum' => 0,
                        'lovetimes' => 0,
                        'looktimes_rate' => 0,
                        'playtimes7_rate' => 0,
                        'playtimes15_rate' => 0,
                        'playtimes21_rate' => 0,
                        'outnum_rate' => 0,
                        'omo_scannum_rate' => 0,
                        'lovetimes_rate' => 0,
                    ];
                }
                $start_date = (new Carbon($start_date))->addDay(1)->toDateString();
            }
            return $output;
        } else {
            return $data;
        }
    }

    public function array_multi_search($p_needle, $p_haystack)
    {
        foreach ($p_haystack as $row) {
            if (in_array($p_needle, $row)) {
                return true;
            }
        }
        return false;
    }


    /**
     * 人次用户渗透率（扇形图）
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array
     */
    public function getPermeabilityByGender(ChartDataRequest $request, Builder $query)
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

    private function getGenderGroupByAge(string $gender, Builder $query)
    {
        $suffix = $gender == 'male' ? 'b' : 'g';
        return $query->selectRaw("sum(age10$suffix) as age10,sum(age18$suffix) as age18,sum(age30$suffix) as age30,sum(age40$suffix) as age40,sum(age60$suffix) as age60,sum(age61$suffix) as age61")
            ->first()->toArray();

    }

    private function getGenderAll(Builder $query)
    {
        return $query->selectRaw("sum(gnum) as female,sum(bnum) as male")
            ->first()->toArray();
    }

    /**
     * 人次用户渗透率（柱状图）
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return mixed
     */
    public function getPermeabilityByAge(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        return $this->getAgeGroupByGender($query);
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
     * 获取围观人次时间段与人群特征
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array
     */
    public function getCharacterByTime(ChartDataRequest $request, Builder $query)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $startClientDate = strtotime($startDate) * 1000;
        $endClientDate = strtotime($endDate) * 1000;

        $this->handleQuery($request, $query);
        $data = $query->whereRaw("xs_face_character_count_times.clientdate between '$startClientDate' and '$endClientDate'")
            ->groupBy('time')
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
     * 节目日化人气
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array
     */
    public function getTopProjects(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query, false);
        $table = $query->getModel()->getTable();
        $data = $query->selectRaw("ar_product_list.name as product_name,belong,round(sum(looktimes)/count($table.date),0) as looktimes,round(sum(playtimes7)/count($table.date),0) as playtimes7,round(sum(playtimes15)/count($table.date),0) as playtimes15,round(sum(playtimes21)/count($table.date),0) as playtimes21, round(sum(outnum)/count($table.date),0) as outnum,round(sum(omo_scannum)/count($table.date),0) as omo_scannum,round(sum(lovetimes)/count($table.date),0) as lovetimes")
            ->groupBy('belong')
            ->orderBy('looktimes', 'desc')
            ->limit(10)
            ->get();

        $output = [];
        foreach ($data as $item) {
            $output[] = [
                'display_name' => $item->product_name,
                'index' => $item->belong,
                'count' => [
                    'looktimes' => $item->looktimes,
                    'playtimes7' => $item->playtimes7,
                    'playtimes15' => $item->playtimes15,
                    'playtimes21' => $item->playtimes21,
                    'outnum' => $item->outnum,
                    'omo_scannum' => $item->omo_scannum,
                    'lovetimes' => $item->lovetimes,
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

    /**
     * @param Request $request
     * @param Builder $query
     * @param bool $selectByAlias
     * @param bool $selectPoint
     */
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