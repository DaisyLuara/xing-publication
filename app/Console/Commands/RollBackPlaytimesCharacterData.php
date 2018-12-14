<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FacePlayCharacterRecord;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class RollBackPlaytimesCharacterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:rollback_playtimes_character';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据回滚7s,15s,21s人次人群特征';

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

        if ($date >= '2018-07-24') {
            FacePlayCharacterRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            FacePlayCharacterRecord::create(['date' => $date]);
        } else {
            FacePlayCharacterRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-07-24'")->delete();
        }

        DB::connection('ar')->table('xs_face_playtimes7_character_count')->whereRaw("date_format(date,'%Y-%m-%d') >='$date'")->delete();
        DB::connection('ar')->table('xs_face_playtimes15_character_count')->whereRaw("date_format(date,'%Y-%m-%d') >='$date'")->delete();
        DB::connection('ar')->table('xs_face_playtimes21_character_count')->whereRaw("date_format(date,'%Y-%m-%d') >='$date'")->delete();
    }
}
