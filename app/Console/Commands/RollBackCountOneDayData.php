<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class RollBackCountOneDayData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:rollback_faceCount_oneDay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据回滚face_count的某一天数据';

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
        $date = $this->ask("输入回滚时间：");
        if ($date < '2017-04-21' || $date >= Carbon::now()->toDateString()) {
            return $this->error('时间输入有误！');
        }
        DB::connection('ar')->table('xs_face_active_player')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_active_playtimes')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_omo')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_phone')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_phone_times')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_count_log')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_look_times')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_verify_times')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
        DB::connection('ar')->table('xs_face_coupon_times')->whereRaw("date_format(date,'%Y-%m-%d')='$date'")->delete();
    }
}
