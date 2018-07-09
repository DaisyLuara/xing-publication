<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCollectRecord;
use DB;
use Carbon\Carbon;

class FaceCollectCharacter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:face_collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'face_collect 数据清洗';

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
        $date = FaceCollectRecord::query()->max('date');
        $date = (new Carbon($date))->format('Y-m-d');
        $currentDate = Carbon::now()->toDateString();

        while ($date < $currentDate) {
            $startDate = strtotime($date . " 00:00:00") * 1000;
            $endDate = strtotime($date . " 23:59:59") * 1000;

            $century00 = "when age > 8 and age <= 18 then '00'";
            $century90 = "when age > 18 and age <= 28 then '90' ";
            $century80 = "when age > 18 and age <= 38 then '80' ";
            $century70 = "when age > 38 and age <= 48 then '70' ";
            $century = $century00 . $century90 . $century80 . $century70;

            $sql = DB::connection('ar')->table('face_collect')
                ->selectRaw("date_format(concat(date(date), ' ', hour(date), ':', floor(minute(date) / 30) * 30), '%Y-%m-%d %H:%i') as time,case " . $century . "else 0 end as century,gender,oid,belong")
                ->whereRaw("clientdate between '$startDate' and '$endDate' and fpid > 0 and type = 'play' ")
                ->orderBy('isold');

            //按所有人去重 belong='all'
            if ($date < '2018-07-01') {
                $sql1 = $sql->groupBy(DB::raw('fpid*100+oid'));
            } else {
                $sql1 = $sql->groupBy(DB::raw('fpid*10000+oid'));
            }
            $allData = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
                ->groupBy(DB::raw("oid,time,century,gender"))
                ->orderBy(DB::raw("oid,time,century,gender"))
                ->selectRaw("oid,time,century,gender,count(*) as looknum")
                ->get();

            //按节目去重
            if ($date < '2018-07-01') {
                $sql2 = $sql->groupBy(DB::raw('fpid*100+oid,belong'));
            } else {
                $sql2 = $sql->groupBy(DB::raw('fpid*10000+oid,belong'));
            }
            $data = DB::connection('ar')->table(DB::raw("({$sql2->toSql()}) as a"))
                ->groupBy(DB::raw("oid,belong,time,century,gender"))
                ->orderBy(DB::raw("oid,belong,time,century,gender"))
                ->selectRaw("oid,belong,time,century,gender,count(*) as looknum")
                ->get();

            $count = [];
            foreach ($allData as $item) {
                $item = json_decode(json_encode($item), true);
                $item['belong'] = 'all';
                $item['time'] = (new Carbon($item['time']))->addMinutes(30)->format('H:i');
                $item['date'] = $date;
                $item['clientdate'] = strtotime($date) * 1000;
                $count[] = $item;
            }
            foreach ($data as $item) {
                $item = json_decode(json_encode($item), true);
                $item['time'] = (new Carbon($item['time']))->addMinutes(30)->format('H:i');
                $item['date'] = $date;
                $item['clientdate'] = strtotime($date) * 1000;
                $count[] = $item;
            }
            $count = array_chunk($count, 8000);
            for ($i = 0; $i < count($count); $i++) {
                DB::connection('ar')->table('face_collect_character')->insert($count[$i]);
            }
            $date = (new Carbon($date))->addDay(1)->toDateString();
        }
        FaceCollectRecord::create(['date' => $currentDate]);
    }
}
