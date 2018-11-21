<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlayerRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlaytimesRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceLookTimesRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceOmoRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FacePhoneRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceVerifyRecord;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class RollbackCountData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:rollback_faceCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据回滚face_count';

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

        //rollback records
        if ($date >= '2018-06-13') {
            FaceActivePlayerRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            FaceActivePlayerRecord::create(['date' => $date]);
        } else {
            FaceActivePlayerRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-06-13'")->delete();
        }

        if ($date >= '2018-07-24') {
            FaceActivePlaytimesRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            FaceActivePlaytimesRecord::create(['date' => $date]);
        } else {
            FaceActivePlaytimesRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-07-24'")->delete();
        }

        if ($date >= '2018-07-19') {
            FaceOmoRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            FaceOmoRecord::create(['date' => $date]);
        } else {
            FaceOmoRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-07-19'")->delete();
        }

        if ($date >= '2018-11-15') {
            FaceVerifyRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            FaceVerifyRecord::create(['date' => $date]);
        } else {
            FaceVerifyRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-07-19'")->delete();
        }

        FacePhoneRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        FacePhoneRecord::create(['date' => $date]);

        FaceCountRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        FaceCountRecord::create(['date' => $date]);

        FaceLookTimesRecord::query()->whereRaw("date_format(date,%Y-%m-%d)>='$date'")->delete();
        FaceLookTimesRecord::create(['date' => $date]);

        //rollback data
        DB::connection('ar')->table('xs_face_active_player')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::connection('ar')->table('xs_face_active_playtimes')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::connection('ar')->table('xs_face_omo')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::connection('ar')->table('xs_face_phone')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::connection('ar')->table('xs_face_phone_times')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::connection('ar')->table('xs_face_count_log')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::connection('ar')->table('xs_face_look_times')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::connection('ar')->table('xs_face_verify_times')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
    }
}