<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceCharacterRecord;
use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class RollBackCharacterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:rollback_looknum_character';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据回滚围观人数人群特征';

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

        FaceCharacterRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        FaceCharacterRecord::create(['date' => $date]);

        $clientdate = strtotime($date) * 1000;
        DB::connection('ar')->table('xs_face_character')->whereRaw("clientdate>='$clientdate'")->delete();
        DB::connection('ar')->table('xs_face_character_count')->whereRaw("clientdate>='$clientdate'")->delete();
    }
}
