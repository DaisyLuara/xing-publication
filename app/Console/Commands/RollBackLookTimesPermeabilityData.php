<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord;
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
    protected $signature = 'yqDataClean:rollback_looktimes_permeability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据回滚围观人次渗透率';

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
            FaceLooktimesPermeabilityRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            FaceLooktimesPermeabilityRecord::create(['date' => $date]);
        } else {
            FaceLooktimesPermeabilityRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-07-24'")->delete();
        }

        DB::connection('ar')->table('xs_face_log_times')->whereRaw("date_format(date,'%Y-%m-%d') >='$date'")->delete();
    }
}
