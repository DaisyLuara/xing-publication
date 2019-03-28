<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlayerRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlaytimesRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCouponRecord;
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

        FaceCountRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        FaceCountRecord::create(['date' => $date]);

        //rollback data
        DB::connection('ar')->table('xs_face_count_log')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
    }
}
