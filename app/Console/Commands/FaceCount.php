<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord;
use Carbon\Carbon;

class FaceCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:face_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '聚合face_count_log和active_player';

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
        $date = FaceCountRecord::query()->max('date');
        $date = (new Carbon($date))->format('Y-m-d');
        $currentDate = Carbon::now()->toDateString();
        while ($date < $currentDate) {
            $sql1 = DB::connection('ar')->table('face_count_log as fcl')
                ->whereRaw("date_format(fcl.date,'%Y-%m-%d')='$date'")
                ->selectRaw("fcl.oid,fcl.belong,looknum,playernum,outnum,scannum,lovenum");

            $clientDate = strtotime($date . ' 00:00:00') * 1000;
            $sql2 = DB::connection('ar')->table('xs_face_active_player')
                ->whereRaw("clientdate='$clientDate'")
                ->selectRaw("oid,belong,playernum7,playernum20,playernum30");

            $data = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
                ->join(DB::raw("({$sql2->toSql()}) as b"), function ($join) {
                    $join->on('a.oid', '=', 'b.oid');
                    $join->on('a.belong', '=', 'b.belong');
                }, null, null, 'left')
                ->selectRaw("a.oid as oid,a.belong as belong,looknum,playernum7,playernum20,playernum30,playernum,outnum,scannum,lovenum")
                ->get();
            $count = [];
            foreach ($data as $item) {
                $count[] = [
                    'oid' => $item->oid,
                    'belong' => $item->belong,
                    'looknum' => $item->looknum,
                    'playernum7' => $item->playernum7 ? $item->playernum7 : 0,
                    'playernum20' => $item->playernum20 ? $item->playernum20 : 0,
                    'playernum30' => $item->playernum30 ? $item->playernum30 : 0,
                    'playernum' => $item->playernum,
                    'outnum' => $item->outnum,
                    'scannum' => $item->scannum,
                    'lovenum' => $item->lovenum,
                    'date' => $date
                ];
            }
            DB::connection('ar')->table('xs_face_count_log')->insert($count);
            $date = (new Carbon($date))->addDay(1)->toDateString();
        }
        FaceCountRecord::create(['date' => $currentDate]);
    }
}
