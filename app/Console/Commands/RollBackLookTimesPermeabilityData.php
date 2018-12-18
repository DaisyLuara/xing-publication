<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceLogTimesRecord;
use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class RollBackLookTimesPermeabilityData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $date = $this->ask("输入回滚时间：");
        if ($date < '2017-04-21' || $date >= Carbon::now()->toDateString()) {
            return $this->error('时间输入有误！');
        }

        if ($date >= '2018-07-24') {
            FaceLogTimesRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            FaceLogTimesRecord::create(['date' => $date]);
        } else {
            FaceLogTimesRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-07-24'")->delete();
        }

        DB::connection('ar')->table('xs_face_character_count_times')->whereRaw("date_format(date,'%Y-%m-%d') >='$date'")->delete();
    }
}
