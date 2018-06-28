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
        $schedule->call(function () {
            $data = $this->getTenUser();
            for ($i = 0; $i < 2; $i++) {
                //yq,cz
                $openId = ['oNN6q0sZDI_OSTV6rl0rPeHjPgH8', 'oNN6q0pq-f0-Z2E2gb0QeOmY4r-M'];
                WeekRankingJob::dispatch($data[$i], $openId[$i])->onQueue('weekRanking');
            }
        })->weekly()->fridays()->at('10:00');

        $schedule->call(function () {
            $max_id = FacePeopleTimeRecord::query()->max('max_id');
            $data = DB::connection('ar')->table('face_people_time')
                ->where('id', '>', $max_id)
                ->groupBy('oid')
                ->groupBy('belong')
                ->groupBy(DB::raw("date_format(date,'%Y-%m-%d')"))
                ->orderBy('date')
                ->orderBy('oid')
                ->selectRaw("oid,belong,count(*) as playernum,sum(playtime) as playtime,date_format(date,'%Y-%m-%d') as date")
                ->get();
            $count = [];
            foreach ($data as $item) {
                $item = json_decode(json_encode($item), true);
                $count[] = $item;
            }
            DB::connection('ar')->table('face_people_time_count')
                ->insert($count);

            $max_id = DB::connection('ar')->table('face_people_time')
                ->max('id');

            FacePeopleTimeRecord::create(['max_id' => $max_id]);
        })->daily()->at('8:00');

        $schedule->call(function () {
            $date = Carbon::now()->format('Y-m');
            $sql = DB::connection('ar')->table('face_people_time')
                ->groupBy(DB::raw('oid'))
                ->groupBy(DB::raw('belong'))
                ->groupBy(DB::raw('fpid'))
                ->groupBy(DB::raw('month'))
                ->whereRaw("date_format(date,'%Y-%m') = '$date' and playtime > 7000")
                ->selectRaw("oid,belong,date_format(date,'%Y-%m') as month");
            $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                ->groupBy(DB::raw('a.oid'))
                ->groupBy(DB::raw('a.belong'))
                ->selectRaw("a.oid as oid,a.belong as belong,count(*) as playernum")
                ->get();

            $date = Carbon::now()->format('Y-m-d');
            $count = [];
            foreach ($data as $item) {
                $item = json_decode(json_encode($item), true);
                $item['date'] = $date;
                $count[] = $item;
            }

            DB::connection('ar')->table('face_people_time_mau')
                ->insert($count);
        })->monthlyOn(1, '8:00');

        $schedule->call(function () {
            $date = FaceCollectRecord::query()->max('date');
            $date = (new Carbon($date))->format('Y-m-d');
            $currentDate = Carbon::now()->toDateString();

            while ($date < $currentDate) {
                $startDate = strtotime($date . " 00:00:00") * 1000;
                $endDate = strtotime($date . " 23:59:59") * 1000;

                $clientDate1 = strtotime($date . " 10:00:00") * 1000;
                $clientDate2 = strtotime($date . " 11:59:59") * 1000;
                $clientDate3 = strtotime($date . " 12:00:00") * 1000;
                $clientDate4 = strtotime($date . " 13:59:59") * 1000;
                $clientDate5 = strtotime($date . " 14:00:00") * 1000;
                $clientDate6 = strtotime($date . " 15:59:59") * 1000;
                $clientDate7 = strtotime($date . " 16:00:00") * 1000;
                $clientDate8 = strtotime($date . " 17:59:59") * 1000;
                $clientDate9 = strtotime($date . " 18:00:00") * 1000;
                $clientDate10 = strtotime($date . " 19:59:59") * 1000;
                $clientDate11 = strtotime($date . " 20:00:00") * 1000;
                $clientDate12 = strtotime($date . " 21:59:59") * 1000;
                $clientDate13 = strtotime($date . " 22:00:00") * 1000;

                $time1 = "when clientdate < '$clientDate1' then '10:00' ";
                $time2 = "when clientdate between '$clientDate1' and '$clientDate2' then '12:00' ";
                $time3 = "when clientdate between '$clientDate3' and '$clientDate4' then '14:00' ";
                $time4 = "when clientdate between '$clientDate5' and '$clientDate6' then '16:00' ";
                $time5 = "when clientdate between '$clientDate7' and '$clientDate8' then '18:00' ";
                $time6 = "when clientdate between '$clientDate9' and '$clientDate10' then '20:00' ";
                $time7 = "when clientdate between '$clientDate11' and '$clientDate12' then '22:00' ";
                $time8 = "when clientdate > '$clientDate13' then '24:00' ";
                $time = $time1 . $time2 . $time3 . $time4 . $time5 . $time6 . $time7 . $time8;

                $century00 = "when age>8 and age<=18 then '00'";
                $century90 = "when age>18 and age<=28 then '90' ";
                $century80 = "when age>18 and age<=38 then '80' ";
                $century70 = "when age>38 and age<=48 then '70' ";
                $century = $century00 . $century90 . $century80 . $century70;

                $sql = DB::connection('ar')->table('face_collect')
                    ->selectRaw("case " . $time . "else 0 end as time,case " . $century . "else 0 end as century,gender,oid,belong")
                    ->whereRaw("clientdate between '$startDate' and '$endDate'")
                    ->groupBy(DB::raw('oid,belong,fpid'));
                $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                    ->groupBy(DB::raw("oid,belong,time,century,gender"))
                    ->orderBy(DB::raw("oid,belong,time,century,gender"))
                    ->selectRaw("oid,belong,time,century,gender,count(*) as looknum")
                    ->get();

                $count = [];
                foreach ($data as $item) {
                    $item = json_decode(json_encode($item), true);
                    $item['date'] = $date;
                    $count[] = $item;
                }
                DB::connection('ar')->table('face_collect_character')
                    ->insert($count);
                $date = (new Carbon($date))->addDay(1)->toDateString();
            }
            FaceCollectRecord::create(['date' => $currentDate]);
        })->daily()->at('14:23');
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

    protected function getTenUser()
    {

        //$startDate = Carbon::now()->addDay(-12)->toDateString();
        //$endDate = Carbon::now()->addDay(-5)->toDateString();
        $startDate = '2018-04-01';
        $endDate = '2018-04-07';

        $totalPoint = DB::connection('ar')->table('face_count_log')
            ->whereRaw(" date_format(date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('oid')
            ->selectRaw("oid")
            ->get();
        $limitNum = floor($totalPoint->count() / 10);

        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('admin_per_oid as apo', 'fcl.oid', '=', 'apo.oid')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitNum)
            ->selectRaw("  apo.uid as uid,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $data = [];
        $faceCount->each(function ($item) use (&$data, $startDate, $endDate) {
            WeekRanking::create([
                'ar_user_id' => $item->uid,
                'point_id' => $item->oid,
                'looknum_average' => round($item->looknum / 7, 0),
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            $data[] = [
                'uid' => $item->uid,
                'pointName' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'looknum' => round($item->looknum / 7, 0)
            ];
        });
        return $data;
    }
}
