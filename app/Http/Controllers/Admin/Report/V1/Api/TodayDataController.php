<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/6
 * Time: 下午2:19
 */

namespace App\Http\Controllers\Admin\Report\V1\Api;


use App\Http\Controllers\Admin\Report\V1\Models\XsFaceCountToday;
use App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesCharacterToday;
use App\Http\Controllers\Admin\Report\V1\Models\XsLookTimesPermeabilityToday;
use App\Http\Controllers\Admin\Report\V1\Request\TodayDataRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class TodayDataController extends Controller
{

    public function chart(TodayDataRequest $request)
    {
        $faceCountQuery = XsFaceCountToday::query();
        $permeabilityQuery = XsLookTimesPermeabilityToday::query();
        $characterQuery = XsLookTimesCharacterToday::query();
        switch ($request->id) {
            case 1:
                $data = $this->getFaceCount($request, $faceCountQuery);
                break;
            case 2:
                $data = $this->getPermeability($request, $permeabilityQuery);
                break;
            case 3:
                $data = $this->getCharacter($request, $characterQuery);
                break;
            case 4:
                $data = $this->getAreaDistribution($request, $faceCountQuery);
                break;
            default :
                return null;
        }
        return response()->json($data);
    }

    public function getFaceCount($request, Builder $query)
    {
        if ($request->has('belong')) {
            $belong = explode(',', $request->belong);
            $query->whereIn('belong', $belong);
        }

        $date = Carbon::now()->toDateString();
        $data = $query->whereRaw("date_format(date,'%Y-%m-%d')= '$date' ")
            ->selectRaw("sum(exposuretimes) as exposuretimes,sum(looktimes) as looktimes ,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(scantimes) as scantimes")
            ->first()->toArray();
        $output = [
            "data" => [
                'exposuretimes' => $data['exposuretimes'] == 0 ? 0 : intval($data['exposuretimes']),
                'looktimes' => $data['looktimes'] == 0 ? 0 : intval($data['looktimes']),
                'playtimes7' => $data['playtimes7'] == 0 ? 0 : intval($data['playtimes7']),
                'playtimes15' => $data['playtimes15'] == 0 ? 0 : intval($data['playtimes15']),
                'scantimes' => $data['scantimes'] == 0 ? 0 : intval($data['scantimes'])
            ],
            'rate' => [
                'CPM' => $data['exposuretimes'] == 0 ? 0 : round($data['looktimes'] / $data['exposuretimes'], 3) * 100,
                '7fCPE' => $data['looktimes'] == 0 ? 0 : round($data['playtimes7'] / $data['looktimes'], 3) * 100,
                '15fCPE' => $data['looktimes'] == 0 ? 0 : round($data['playtimes15'] / $data['looktimes'], 3) * 100,
                'fCPA' => $data['looktimes'] == 0 ? 0 : round($data['scantimes'] / $data['looktimes'], 3) * 100,
            ]
        ];

        $type = "total";
        if ($request->has('belong')) {
            $type = $request->belong;
        }
        $output = $this->checkCache($output, $type, 'api_1');
        return $output;
    }


    public function getPermeability($request, Builder $query)
    {
        $query_all = XsLookTimesPermeabilityToday::query();
        if ($request->has('belong')) {
            $belong = explode(',', $request->belong);
            $query->whereIn('belong', $belong);
            $query_all->whereIn('belong', $belong);
        }
        $date = Carbon::now()->toDateString();
        $allData = $query_all->whereRaw("date_format(date,'%Y-%m-%d')= '$date' ")
            ->selectRaw("sum(bnum) as bnum,sum(gnum) as gnum,sum(bnum+gnum) as total")
            ->first()->toArray();
        $data = $query->whereRaw("date_format(date,'%Y-%m-%d')= '$date' ")
            ->selectRaw("sum(age10b) as age10_male,sum(age10g) as age10_female,
                                    sum(age18b) as age18_male,sum(age18g) as age18_female,
                                    sum(age30b) as age30_male,sum(age30g) as age30_female,
                                    sum(age40b) as age40_male,sum(age40g) as age40_female,
                                    sum(age50b) as age50_male,sum(age50g) as age50_female,
                                    sum(age60b) as age60_male,sum(age60g) as age60_female,
                                    sum(age61b) as age61_male,sum(age61g) as age61_female")
            ->first()->toArray();
        $count = [];
        foreach ($data as $key => $value) {
            $keys = explode('_', $key);
            $count[$keys[0]][$keys[1]] = $value == 0 ? 0 : intval($value);
        }
        $output = [];
        $output['total'] = [
            'count' => [
                'male' => $allData['bnum'] == 0 ? 0 : intval($allData['bnum']),
                'female' => $allData['gnum'] == 0 ? 0 : intval($allData['gnum']),

            ],
            'rate' => [
                'male' => $allData['total'] == 0 ? 0 : round($allData['bnum'] / $allData['total'], 3) * 100,
                'female' => $allData['total'] == 0 ? 0 : round($allData['gnum'] / $allData['total'], 3) * 100
            ]
        ];
        $ageMapping = [
            'age10' => '0-10岁',
            'age18' => '11-18岁',
            'age30' => '19-30岁',
            'age40' => '31-40岁',
            'age50' => '41-50岁',
            'age60' => '51-60岁',
            'age61' => '60岁以上',
        ];
        foreach ($count as $key => $value) {
            $output['group'][] = [
                'count' => $value,
                'rate' => $allData['total'] == 0 ? 0 : round(($value['female'] + $value['male']) / $allData['total'], 3) * 100,
                'display_name' => $ageMapping[$key]
            ];
        }

        $type = "total";
        if ($request->has('belong')) {
            $type = $request->belong;
        }
        $output = $this->checkCache($output, $type, 'api_2');
        return $output;
    }

    public function getCharacter($request, Builder $query)
    {
        if (!$request->has("belong")) {
            abort(422, "节目必填");
        }
        $belong = explode(',', $request->belong);
        $date = Carbon::now()->toDateString();
        $data = $query->whereRaw("date_format(date,'%Y-%m-%d')= '$date' ")
            ->whereIn("belong", $belong)
            ->selectRaw("sum(century10_gnum+century00_gnum+century90_gnum+century80_gnum+century70_gnum) as gnum,
                              sum(century10_bnum+century00_bnum+century90_bnum+century80_bnum+century70_bnum) as bnum,time")
            ->groupBy("time")
            ->get()
            ->toArray();
        $displayTime = [
            '10:00',
            '12:00',
            '14:00',
            '16:00',
            '18:00',
            '20:00',
            '22:00',
            '24:00',
        ];
        $output = [];
        foreach ($displayTime as $key => $value) {
            $arr = array_filter($data, function ($aa) use ($value) {
                return $aa['time'] == $value;
            });
            if (empty($arr)) {
                $arr = [['bnum' => 0, 'gnum' => 0]];
            }
            $item = array_values($arr)[0];
            $total = $item['bnum'] + $item['gnum'];
            $output[] = [
                'display_name' => $value,
                'count' => [
                    'male' => $item['bnum'] == 0 ? 0 : intval($item['bnum']),
                    'female' => $item['gnum'] == 0 ? 0 : intval($item['gnum'])
                ],
                'rate' => [
                    'male' => $total == 0 ? 0 : round($item['bnum'] / $total, 3) * 100,
                    'female' => $total == 0 ? 0 : round($item['gnum'] / $total, 3) * 100
                ]
            ];
        }
        $output = $this->checkCache($output, $request->belong, 'api_3');
        return $output;
    }

    public function getAreaDistribution($request, Builder $query)
    {
        $total = XsFaceCountToday::query()->selectRaw("sum(exposuretimes) as num")->first()->toArray();
//        $case1 = "when oid=739 or oid=740 or oid=741 then 'A' ";
//        $case2 = "when oid=742 or oid=743 or oid=744 then 'B' ";
//        $case3 = "when oid=746 or oid=747 then 'C' ";
//        $case4 = "when oid=748 then 'D' ";
//        $sql = $case1 . $case2 . $case3 . $case4;
        #TODO 美陈展点位过滤和更改区域划分
        $sql = "when oid=420 then 'A' when oid=421 then 'B' when oid=422 then 'C' when oid=423 then 'D' ";
        $date = Carbon::now()->toDateString();
        $data = $query->whereRaw("date_format(date,'%Y-%m-%d')= '$date' ")
            ->selectRaw("case " . $sql . "else 0 end as area,sum(exposuretimes) as num")
            ->groupBy("area")
            ->orderBy('num', 'desc')
            ->get();
        $output = [];
        $areaMapping = [
            'A' => '夺宝阵地区',
            'B' => '幸运地标区',
            'C' => '街拍围挡区',
            'D' => '红包大屏区'
        ];
        foreach ($data as $item) {
            if ($item['area'] === "0") {
                continue;
            }
            $output[] = [
                'display_name' => $areaMapping[$item['area']],
                'count' => intval($item['num']),
                'rate' => $total['num'] == 0 ? 0 : round($item['num'] / $total['num'], 3) * 100
            ];
        }
        $output = $this->checkCache($output, 'total', 'api_4');
        return $output;
    }


    private function checkCache($output, $type, $api)
    {
        if (Cache::has($type . '_' . $api)) {
            $oldData = Cache::get($type . '_' . $api);
            if ($this->compare($oldData, $output, $api)) {
                return $oldData;
            }
        }
        $minutes = Carbon::now()->endOfDay()->diffInMinutes(Carbon::now());
        Cache::put($type . '_' . $api, $output, $minutes);
        return $output;
    }

    private function compare($oldData, $output, $api)
    {
        $currentCount = 0;
        $oldCount = 0;
        if ($api == 'api_1') {
            $oldCount = $oldData['data']['looktimes'];
            $currentCount = $output['data']['looktimes'];
        }

        if ($api == 'api_2') {
            $oldCount = $oldData['total']['count']['male'] + $oldData['total']['count']['female'];
            $currentCount = $output['total']['count']['male'] + $output['total']['count']['female'];
        }

        if ($api == 'api_3') {
            $oldCount = array_sum(array_column(array_column($oldData, 'count'), 'male'))
                + array_sum(array_column(array_column($oldData, 'count'), 'female'));
            $currentCount = array_sum(array_column(array_column($output, 'count'), 'male'))
                + array_sum(array_column(array_column($output, 'count'), 'female'));
        }

        if ($api == 'api_4') {
            $oldCount = array_sum(array_column($oldData, 'count'));
            $currentCount = array_sum(array_column($output, 'count'));
        }

        if ($currentCount < $oldCount) {
            return true;
        }
        return false;
    }
}