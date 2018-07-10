<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class FaceCharacterCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:face_character_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $date = DB::table('face_character_count_records')->max('date');
        $date = (new Carbon($date))->format('Y-m-d');
        $currentDate = Carbon::now()->toDateString();
        while ($date < $currentDate) {
            $clientDate = strtotime($date . ' 00:00:00') * 1000;
            $time1 = " when time >'00:00' and time <='10:00' then '10:00'";
            $time2 = " when time >'10:00' and time <='12:00' then '12:00'";
            $time3 = " when time >'12:00' and time <='14:00' then '14:00'";
            $time4 = " when time >'14:00' and time <='16:00' then '16:00'";
            $time5 = " when time >'16:00' and time <='18:00' then '18:00'";
            $time6 = " when time >'18:00' and time <='20:00' then '20:00'";
            $time7 = " when time >'20:00' and time <='22:00' then '22:00'";
            $time8 = " when time >'22:00' or time ='00:00' then '24:00'";
            $timeSql = $time1 . $time2 . $time3 . $time4 . $time5 . $time6 . $time7 . $time8;

            $sql = DB::connection('ar')->table('xs_face_character')
                ->whereRaw("clientdate='$clientDate'")
                ->groupBy(DB::raw("oid,belong,times,century,gender"))
                ->selectRaw("oid,belong,case" . $timeSql . " else 0 end as times,century,gender,sum(looknum) as looknum");

            $sum1 = "sum(if(century = '00' and gender = 'Male', looknum, 0))   as century00_bnum,";
            $sum2 = " sum(if(century = '00' and gender = 'Female', looknum, 0)) as century00_gnum,";
            $sum3 = " sum(if(century = '90' and gender = 'Male', looknum, 0))   as century90_bnum,";
            $sum4 = " sum(if(century = '90' and gender = 'Female', looknum, 0)) as century90_gnum,";
            $sum5 = " sum(if(century = '80' and gender = 'Male', looknum, 0))   as century80_bnum,";
            $sum6 = " sum(if(century = '80' and gender = 'Female', looknum, 0)) as century80_gnum,";
            $sum7 = " sum(if(century = '70' and gender = 'Male', looknum, 0))   as century70_bnum,";
            $sum8 = " sum(if(century = '70' and gender = 'Female', looknum, 0)) as century70_gnum,";
            $sum9 = " sum(if(century = '0' and gender = 'Male', looknum, 0))    as century0_bnum,";
            $sum10 = " sum(if(century = '0' and gender = 'Female', looknum, 0)) as century0_gnum";
            $sum = $sum1 . $sum2 . $sum3 . $sum4 . $sum5 . $sum6 . $sum7 . $sum8 . $sum9 . $sum10;
            $data = DB::table(DB::raw("({$sql->toSql()}) as a"))
                ->groupBy(DB::raw('oid,belong,times'))
                ->selectRaw("oid,belong,times," . $sum)
                ->get();

            $count = [];
            foreach ($data as $item) {
                $count[] = [
                    'oid' => $item->oid,
                    'belong' => $item->belong,
                    'time' => $item->times,
                    'century00_bnum' => $item->century00_bnum ? $item->century00_bnum : 0,
                    'century00_gnum' => $item->century00_gnum ? $item->century00_gnum : 0,
                    'century90_bnum' => $item->century90_bnum ? $item->century90_bnum : 0,
                    'century90_gnum' => $item->century90_gnum ? $item->century90_gnum : 0,
                    'century80_bnum' => $item->century80_bnum ? $item->century80_bnum : 0,
                    'century80_gnum' => $item->century80_gnum ? $item->century80_gnum : 0,
                    'century70_bnum' => $item->century70_bnum ? $item->century70_bnum : 0,
                    'century70_gnum' => $item->century70_gnum ? $item->century70_gnum : 0,
                    'century0_bnum' => $item->century0_bnum ? $item->century0_bnum : 0,
                    'century0_gnum' => $item->century0_gnum ? $item->century0_gnum : 0,
                    'date' => $date,
                    'clientdate' => strtotime($date) * 1000
                ];
            }
            $count = array_chunk($count, 8000);
            for ($i = 0; $i < count($count); $i++) {
                DB::connection('ar')->table('xs_face_character_count')
                    ->insert($count[$i]);
            }
            $date = (new Carbon($date))->addDay(1)->toDateString();
        }
        DB::table('face_character_count_records')->insert(['date' => $currentDate]);

    }
}
