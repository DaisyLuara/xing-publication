<?php

namespace App\Console;

use App\Http\Controllers\Admin\Face\V1\Models\FaceCollectRecord;
use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Jobs\WeekRankingJob;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Admin\Face\V1\Models\FacePeopleTimeRecord;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //点位排名通知
        $schedule->call(function () {
            $data = $this->getRankingData();
            for ($i = 0; $i < count($data); $i++) {
                WeekRankingJob::dispatch($data[$i])->onQueue('weekRanking');
            }
        })->weekly()->fridays()->at('14:20');

        //玩家互动时间清洗
//        $schedule->call(function () {
//            $max_id = FacePeopleTimeRecord::query()->max('max_id');
//            $data = DB::connection('ar')->table('face_people_time')
//                ->where('id', '>', $max_id)
//                ->groupBy('oid')
//                ->groupBy('belong')
//                ->groupBy(DB::raw("date_format(date,'%Y-%m-%d')"))
//                ->orderBy('date')
//                ->orderBy('oid')
//                ->selectRaw("oid,belong,count(*) as playernum,sum(playtime) as playtime,date_format(date,'%Y-%m-%d') as date")
//                ->get();
//            $count = [];
//            foreach ($data as $item) {
//                $item = json_decode(json_encode($item), true);
//                $count[] = $item;
//            }
//            DB::connection('ar')->table('face_people_time_count')
//                ->insert($count);
//
//            $max_id = DB::connection('ar')->table('face_people_time')
//                ->max('id');
//
//            FacePeopleTimeRecord::create(['max_id' => $max_id]);
//        })->daily()->at('8:00');

        //月活玩家清洗
//        $schedule->call(function () {
//            $date = Carbon::now()->addMonth(-1)->format('Y-m');
//            $sql = DB::connection('ar')->table('face_people_time')
//                ->groupBy(DB::raw('oid'))
//                ->groupBy(DB::raw('belong'))
//                ->groupBy(DB::raw('fpid'))
//                ->groupBy(DB::raw('month'))
//                ->whereRaw("date_format(date,'%Y-%m') = '$date' and playtime > 7000")
//                ->selectRaw("oid,belong,date_format(date,'%Y-%m') as month");
//            $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
//                ->groupBy(DB::raw('a.oid'))
//                ->groupBy(DB::raw('a.belong'))
//                ->selectRaw("a.oid as oid,a.belong as belong,count(*) as playernum")
//                ->get();
//
//            $date = Carbon::now()->addMonth(-1)->format('Y-m-d');
//            $count = [];
//            foreach ($data as $item) {
//                $item = json_decode(json_encode($item), true);
//                $item['date'] = $date;
//                $count[] = $item;
//            }
//
//            DB::connection('ar')->table('face_people_time_mau')
//                ->insert($count);
//        })->monthlyOn(1, '8:00');

        //时间段与人群特征数据清洗
//        $schedule->call(function () {
//            $date = FaceCollectRecord::query()->max('date');
//            $date = (new Carbon($date))->format('Y-m-d');
//            $currentDate = Carbon::now()->toDateString();
//
//            while ($date < $currentDate) {
//                $startDate = strtotime($date . " 00:00:00") * 1000;
//                $endDate = strtotime($date . " 23:59:59") * 1000;
//
//                $time1 = "when date_format(date, '%H:%i:%s') < '10:00:00' then '10:00' ";
//                $time2 = "when date_format(date, '%H:%i:%s') between '10:00:00' and '11:59:59' then '12:00' ";
//                $time3 = "when date_format(date, '%H:%i:%s') between '12:00:00' and '13:59:59' then '14:00' ";
//                $time4 = "when date_format(date, '%H:%i:%s') between '14:00:00' and '15:59:59' then '16:00' ";
//                $time5 = "when date_format(date, '%H:%i:%s') between '16:00:00' and '17:59:59' then '18:00' ";
//                $time6 = "when date_format(date, '%H:%i:%s') between '18:00:00' and '19:59:59' then '20:00' ";
//                $time7 = "when date_format(date, '%H:%i:%s') between '20:00:00' and '21:59:59' then '22:00' ";
//                $time8 = "when date_format(date, '%H:%i:%s') > '22:00:00' then '24:00' ";
//                $time = $time1 . $time2 . $time3 . $time4 . $time5 . $time6 . $time7 . $time8;
//
//                $century00 = "when age>8 and age<=18 then '00'";
//                $century90 = "when age>18 and age<=28 then '90' ";
//                $century80 = "when age>18 and age<=38 then '80' ";
//                $century70 = "when age>38 and age<=48 then '70' ";
//                $century = $century00 . $century90 . $century80 . $century70;
//
//                $sql = DB::connection('ar')->table('face_collect')
//                    ->selectRaw("case " . $time . "else 0 end as time,case " . $century . "else 0 end as century,gender,oid,belong")
//                    ->whereRaw("clientdate between '$startDate' and '$endDate'")
//                    ->groupBy(DB::raw('oid,belong,fpid'));
//                $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
//                    ->groupBy(DB::raw("oid,belong,time,century,gender"))
//                    ->orderBy(DB::raw("oid,belong,time,century,gender"))
//                    ->selectRaw("oid,belong,time,century,gender,count(*) as looknum")
//                    ->get();
//
//                $count = [];
//                foreach ($data as $item) {
//                    $item = json_decode(json_encode($item), true);
//                    $item['date'] = $date;
//                    $count[] = $item;
//                }
//                DB::connection('ar')->table('face_collect_character')
//                    ->insert($count);
//                $date = (new Carbon($date))->addDay(1)->toDateString();
//            }
//            FaceCollectRecord::create(['date' => $currentDate]);
//        })->daily()->at('8:00');
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function getRankingData()
    {

        $startDate = Carbon::now()->addDay(-7)->toDateString();
        $endDate = Carbon::now()->addDay(-1)->toDateString();

        $points = getPointsByScene($startDate, $endDate);
        $selectScenes = [
            [
                'sid' => [1, 8],
                'limit' => (floor($points->whereNotIn('sid', [1, 8])->sum() / 100) + 1) * 10,
                'name' => 'other'
            ],
            [
                'sid' => 8,
                'limit' => (floor($points->where('sid', 8)->sum() / 100) + 1) * 10,
                'name' => 'cinema'
            ],
            [
                'sid' => 1,
                'limit' => (floor($points->where('sid', 1)->sum() / 100) + 1) * 10,
                'name' => 'market'
            ],
        ];

        $ranks = [];
        foreach ($selectScenes as $selectScene) {
            $ranks = array_merge(getFaceCountByScene($startDate, $endDate, $selectScene), $ranks);
        }

        return $ranks;
    }
}
