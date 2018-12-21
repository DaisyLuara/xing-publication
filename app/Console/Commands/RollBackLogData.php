<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceLogRecord;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class RollBackLogData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:rollback_looknum_permeability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据回滚围观人数渗透率';

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

        FaceLogRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        FaceLogRecord::create(['date' => $date]);

        DB::connection('ar')->table('xs_face_log')->whereRaw("date_format(date,'%Y-%m-%d') >='$date'")->delete();

    }
}
