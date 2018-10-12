<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class FaceCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:calendar';

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
        $date = $this->ask("输入起始日期：");
        $start_date = (new Carbon($date))->startOfYear()->toDateString();
        $end_date = (new Carbon($date))->endOfYear()->toDateString();
        $date_list = [
            '2018-01-01',
            '2018-02-15',
            '2018-02-16',
            '2018-02-17',
            '2018-02-18',
            '2018-02-19',
            '2018-02-20',
            '2018-02-21',
            '2018-04-05',
            '2018-04-06',
            '2018-04-29',
            '2018-04-30',
            '2018-05-01',
            '2018-06-16',
            '2018-06-17',
            '2018-06-18',
            '2018-09-22',
            '2018-09-23',
            '2018-09-24',
            '2018-10-01',
            '2018-10-02',
            '2018-10-03',
            '2018-10-04',
            '2018-10-05',
            '2018-10-06',
            '2018-10-07',
        ];
        $work_list = [
            '2018-02-11',
            '2018-02-24',
            '2018-04-08',
            '2018-04-28',
            '2018-05-27',
            '2018-09-29',
            '2018-09-30',
        ];
        $count = [];
        while ($start_date <= $end_date) {
            $work = 1;
            $weekend = 0;
            $holiday = 0;
            if ((new Carbon($start_date))->isWeekend()) {
                $weekend = 1;
                $work = 0;
            }
            if (in_array($start_date, $date_list)) {
                $holiday = 1;
                $work=0;
            }
            if ((new Carbon($start_date))->isWeekend() && in_array($start_date, $work_list)) {
                $work = 1;
            }
            $count[] = [
                'date' => $start_date,
                'workday' => $work,
                'weekend' => $weekend,
                'holiday' => $holiday,
            ];
            $start_date = (new Carbon($start_date))->addDay(1)->toDateString();
        }
        DB::connection('ar')->table('xs_calendar')->insert($count);
    }
}
