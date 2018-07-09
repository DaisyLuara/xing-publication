<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\ActivePlayerRecord;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class FaceActivePlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:active_player';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗7s，20s，30s玩家';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = ActivePlayerRecord::query()->max('date');
        $date = (new Carbon($date))->format('Y-m-d');
        $currentDate = Carbon::now()->toDateString();
        while ($date < $currentDate) {
            $startClientDate = strtotime($date . ' 00:00:00') * 1000;
            $endClientDate = strtotime($date . ' 23:59:59') * 1000;

            $timeArray = [7, 20, 30];

            //按所有人去重 belong='all'
            $sql1 = [];
            for ($i = 0; $i < count($timeArray); $i++) {
                $sql = DB::connection('ar')->table('face_people_time')
                    ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                    ->selectRaw("oid ");
                if ($date < '2018-07-01') {
                    $sql = $sql->groupBy(DB::raw('fpid*100+oid'));
                } else {
                    $sql = $sql->groupBy(DB::raw('fpid*10000+oid'));
                }

                $sql1[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                    ->groupBy('oid' . $timeArray[$i])
                    ->selectRaw("oid as oid" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
            }
            $query = DB::connection('ar')->table(DB::raw("({$sql1[0]->toSql()}) as b"));
            for ($i = 1; $i < count($timeArray); $i++) {
                $query = $query->join(DB::raw("({$sql1[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                    $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i]);
                }, null, null, 'left');
            }
            $query->selectRaw("oid" . $timeArray[0] . " as oid");
            for ($i = 0; $i < count($timeArray); $i++) {
                $query->selectRaw("playernum" . $timeArray[$i]);
            }
            $allData = $query->get();

            //按节目去重
            $sql2 = [];
            for ($i = 0; $i < count($timeArray); $i++) {

                $sql = DB::connection('ar')->table('face_people_time')
                    ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                    ->selectRaw("oid,belong");
                if ($date < '2018-07-01') {
                    $sql = $sql->groupBy(DB::raw('fpid*100+oid,belong'));
                } else {
                    $sql = $sql->groupBy(DB::raw('fpid*10000+oid,belong'));
                }
                $sql2[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                    ->groupBy(DB::raw("oid" . $timeArray[$i] . ",belong" . $timeArray[$i]))
                    ->selectRaw("oid as oid" . $timeArray[$i] . ",belong as belong" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
            }
            $query = DB::connection('ar')->table(DB::raw("({$sql2[0]->toSql()}) as b"));
            for ($i = 1; $i < count($timeArray); $i++) {
                $query = $query->join(DB::raw("({$sql2[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                    $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i])
                        ->on('b.belong' . $timeArray[0], '=', 'b' . $i . '.belong' . $timeArray[$i]);
                }, null, null, 'left');
            }
            $query = $query->selectRaw("oid" . $timeArray[0] . " as oid,belong" . $timeArray[0] . " as belong");
            for ($i = 0; $i < count($timeArray); $i++) {
                $query->selectRaw("playernum" . $timeArray[$i]);
            }
            $data = $query->get();


            $count = [];
            foreach ($allData as $item) {
                $count[] = [
                    'oid' => $item->oid,
                    'belong' => 'all',
                    'playernum7' => $item->playernum7,
                    'playernum20' => $item->playernum20,
                    'playernum30' => $item->playernum30,
                    'date' => $date,
                    'clientdate' => strtotime($date) * 1000
                ];
            }
            foreach ($data as $item) {
                $count[] = [
                    'oid' => $item->oid,
                    'belong' => $item->belong,
                    'playernum7' => $item->playernum7,
                    'playernum20' => $item->playernum20,
                    'playernum30' => $item->playernum30,
                    'date' => $date,
                    'clientdate' => strtotime($date) * 1000
                ];
            }
            DB::connection('ar')->table('xs_face_active_player')
                ->insert($count);
            $date = (new Carbon($date))->addDay(1)->toDateString();
        }
        ActivePlayerRecord::create(['date' => $currentDate]);
    }
}
