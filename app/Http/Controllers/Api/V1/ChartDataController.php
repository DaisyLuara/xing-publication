<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ChartDataRequest;
use App\Transformers\FaceCountTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\FaceCount;
use App\Models\FaceLog;
use Carbon\Carbon;
use DB;

class ChartDataController extends Controller
{
    public function index(Request $request)
    {

        $query = FaceCount::query();
        $this->handleQuery($request, $query);

        $faceCount = $query->selectRaw('fclid as id,looknum,playernum,lovenum,outnum,scannum,avr_official.name as point_name,avr_official_market.name as market_name,avr_official_area.name as area_name,face_count_log.date as created_at')
            ->where('fclid', '>', 0)
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
                $data = $this->getTopPoints($request, $faceCountQuery);
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
        $this->handleQuery($request, $query);
        $data = $query->selectRaw('sum(age10b+age10g) as age10,sum(age18b+age18g) as age18,sum(age30b+age30g) as age30,sum(age40b+age40g) as age40,sum(age60b+age60g) as age60,sum(age61b+age61g) as age61')
            ->where('face_log.type', '=', 'looker')
            ->first()->toArray();
        $output = [];
        $ageMapping = [
            'age10' => '0-10岁',
            'age18' => '11-18岁',
            'age30' => '19-30岁',
            'age40' => '31-40岁',
            'age60' => '41-60岁',
            'age61' => '60岁以上',
        ];
        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => $ageMapping[$key],
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
        $data = $query->selectRaw("sum(gnum) as female,sum(bnum) as male")
            ->where('face_log.type', '=', 'looker')
            ->first()->toArray();
        $output = [];
        $genderMapping = [
            'male' => '男',
            'female' => '女',
        ];
        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => $genderMapping[$key],
            ];
        }
        return $output;
    }

    /**
     * 点位排行榜
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getTopPoints(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        $data = $query->selectRaw("sum(looknum) AS count,avr_official.name,avr_official_market.name as market_name")
            ->groupBy('face_count_log.oid')
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

    /**
     * 节目排行榜
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getTopProjects(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query, false);
        $data = $query->selectRaw("sum(looknum) AS count,ar_product_list.name")
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

    /**
     * 获取总的 围观人数 玩家人数 会员人数
     * @param ChartDataRequest $request
     * @param Builder $query
     * @return array
     */
    private function getTotal(ChartDataRequest $request, Builder $query)
    {
        $this->handleQuery($request, $query);
        $data = $query->selectRaw("sum(looknum) AS looknum,sum(playernum) AS playernum,sum(outnum)  AS outnum,sum(outnum)  AS scannum,sum(scannum)  AS scannum,sum(lovenum)  AS lovenum")
            ->first()->toArray();
        $output = [];

        $totalMapping = [
            'looknum' => '围观总数',
            'playernum' => '互动总数',
            'lovenum' => '扫码拉新',
            'outnum' => '生成数',
            'scannum' => '扫码数',
        ];

        foreach ($data as $key => $value) {
            $output[] = [
                'count' => $value,
                'display_name' => $totalMapping[$key],
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
        return $query->selectRaw("date_format(face_count_log.date,'$format') as display_name")
            ->groupBy('display_name')
            ->get();

    }

    public function getAllPeopleByPoint($arUserID = 0)
    {
        $data = DB::connection('ar')->table('face_count_log')
            ->selectRaw("sum(looknum) AS looknum,
                         sum(playernum) AS playernum,
                         sum(lovenum)  AS lovenum,
                         max(face_count_log.clientdate) as max,
                         min(face_count_log.clientdate) as min,
                         face_count_log.oid")
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

    private function handleQuery(Request $request, Builder $query, $selectByAlias = true)
    {
        $user = $this->user();
        $table = $query->getModel()->getTable();
        $arUserID = $request->home_page ? 0 : getArUserID($user, $request);
        handPointQuery($request, $query, $arUserID);

        //节目搜索-注意业务逻辑
        if ($selectByAlias) {
            $alias = $request->alias ? $request->alias : 'all';
            $query->where("belong", '=', $alias);
        } else {
            $query->join('ar_product_list', 'ar_product_list.versionname', '=', "$table.belong");
        }
    }

}
