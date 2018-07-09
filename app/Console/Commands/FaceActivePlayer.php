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
                    ->selectRaw("oid as oid" . $timeArray[$i] . ", belong as belong" . $timeArray[$i] . ", fpid as fpid" . $timeArray[$i]);
                //按所有人去重 belong='all'
                if ($date < '2018-07-01') {
                    $sql1[$i] = $sql->groupBy(DB::raw('fpid*100+oid'));
                } else {
                    $sql1[$i] = $sql->groupBy(DB::raw('fpid*10000+oid'));
                }
            }

            //按节目去重
            $sql2 = [];
            for ($i = 0; $i < count($timeArray); $i++) {
                $sql = DB::connection('ar')->table('face_people_time')
                    ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                    ->selectRaw("oid as oid" . $timeArray[$i] . ", belong as belong" . $timeArray[$i] . ", fpid as fpid" . $timeArray[$i]);
                //按节目去重
                if ($date < '2018-07-01') {
                    $sql2[$i] = $sql->groupBy(DB::raw('fpid*100+oid,belong'));
                } else {
                    $sql2[$i] = $sql->groupBy(DB::raw('fpid*10000+oid,belong'));
                }
            }

            //按所有人去重 belong='all'
            $query = DB::connection('ar')->table(DB::raw("({$sql1[0]->toSql()}) as a"));
            for ($i = 1; $i < count($timeArray); $i++) {
                $query = $query->join(DB::raw("({$sql1[$i]->toSql()}) as a" . $i), function ($join) use ($i, $timeArray) {
                    $join->on('a.fpid' . $timeArray[0], '=', 'a' . $i . '.fpid' . $timeArray[$i])
                        ->on('a.oid' . $timeArray[0], '=', 'a' . $i . '.oid' . $timeArray[$i])
                        ->on('a.belong' . $timeArray[0], '=', 'a' . $i . '.belong' . $timeArray[$i]);
                }, null, null, 'left');
            }
            $query = $query->selectRaw("oid" . $timeArray[0] . " as oid,belong" . $timeArray[0] . " as belong");
            for ($i = 0; $i < count($timeArray); $i++) {
                $query->selectRaw("count(fpid" . "$timeArray[$i]" . ") as playernum" . $timeArray[$i]);
            }
            $allData = $query->groupBy(DB::raw('oid' . $timeArray[0]))->get();

            //按节目去重
            $query = DB::connection('ar')->table(DB::raw("({$sql2[0]->toSql()}) as a"));
            for ($i = 1; $i < count($timeArray); $i++) {
                $query = $query->join(DB::raw("({$sql2[$i]->toSql()}) as a" . $i), function ($join) use ($i, $timeArray) {
                    $join->on('a.fpid' . $timeArray[0], '=', 'a' . $i . '.fpid' . $timeArray[$i])
                        ->on('a.oid' . $timeArray[0], '=', 'a' . $i . '.oid' . $timeArray[$i])
                        ->on('a.belong' . $timeArray[0], '=', 'a' . $i . '.belong' . $timeArray[$i]);
                }, null, null, 'left');
            }
            $query = $query->selectRaw("oid" . $timeArray[0] . " as oid,belong" . $timeArray[0] . " as belong");
            for ($i = 0; $i < count($timeArray); $i++) {
                $query->selectRaw("count(fpid" . "$timeArray[$i]" . ") as playernum" . $timeArray[$i]);
            }
            $data = $query->groupBy(DB::raw('oid' . $timeArray[0] . ',belong' . $timeArray[0]))->get();

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
