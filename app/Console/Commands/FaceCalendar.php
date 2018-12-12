<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;
use GuzzleHttp\Client;

class FaceCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'workday,weekend,holiday';

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
        $year = $this->ask("输入起始日期：");
        $end_date = (new Carbon($year))->endOfYear()->toDateString();

        //获取法定假日列表和周末补班列表
        $url = 'http://api.goseek.cn/Tools/holiday?date=';
        $holiday_list = [];
        $workday_list = [];
        $date = (new Carbon($year))->startOfYear()->toDateString();
        while ($date <= $end_date) {
            $reqDate = (new Carbon($date))->format('Ymd');
            $client = new Client();
            $res = json_decode($client->request('get', $url . $reqDate)->getBody());
            //工作日0，休息日1，法定假日2
            if ($res->data == 2) {
                $holiday_list[] = $date;
            }
            if ($res->data == 0 && (new Carbon($date))->isWeekend()) {
                $workday_list[] = $date;
            }
            $date = (new Carbon($date))->addDay(1)->toDateString();
        }

        //确定每天属性存入数据库
        $count = [];
        $start_date = (new Carbon($year))->startOfYear()->toDateString();
        while ($start_date <= $end_date) {
            $work = 1;
            $weekend = 0;
            $holiday = 0;
            if ((new Carbon($start_date))->isWeekend()) {
                $weekend = 1;
                $work = 0;
            }
            if (in_array($start_date, $holiday_list)) {
                $holiday = 1;
                $work = 0;
            }
            if ((new Carbon($start_date))->isWeekend() && in_array($start_date, $workday_list)) {
                $work = 1;
            }
            $count[] = [
                'date' => $start_date,
                'workday' => $work,
                'weekend' => $weekend,
                'holiday' => $holiday,
                'clientdate' => strtotime($start_date) * 1000
            ];
            $start_date = (new Carbon($start_date))->addDay(1)->toDateString();
        }
        DB::connection('ar')->table('xs_calendar')->insert($count);

//        更快但需要授权码
//        $date = '2018-01-01';
//        $end_date = (new Carbon($date))->endOfYear()->toDateString();
//        $url = 'http://www.easybots.cn/api/holiday.php?d=';
//
//        $allDate = [];
//        while ($date <= $end_date) {
//            $reqDate = (new Carbon($date))->format('Ymd');
//            $allDate[] = $reqDate;
//            $date = (new Carbon($date))->addDay(1)->toDateString();
//        }
//        $day_str = join(',', $allDate);
//        $client = new Client();
//        $data = json_decode($client->request('get', $url . $day_str)->getBody());
//
//        $holiday_list = [];
//        $workday_list = [];
//        foreach ($data as $key => $value) {
//            if ($value == 2) {
//                $holiday_list[] = (new Carbon($key))->format('Y-m-d');
//            }
//
//            if ($value == 0 && (new Carbon($key))->isWeekend()) {
//                $workday_list[] = (new Carbon($key))->format('Y-m-d');
//            }
//        }
    }
}

