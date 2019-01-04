<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RollbackTeamData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:rollback_team_bonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据回滚团队绩效';

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
        $date = Carbon::parse($this->ask("输入回滚日期(eg: 2018-01-01)："))->toDateString();
        if ($date < '2017-04-21' || $date >= Carbon::now()->toDateString()) {
            return $this->error('时间输入有误！');
        }

        $now = date("Y-m-d H:i:s");
        DB::beginTransaction();
        try {
            if ($date >= '2018-11-21') {
                DB::table('team_bonus_records')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
                DB::table('team_bonus_records')->create(['date' => $date, 'created_at' => $now]);
            } else {
                DB::table('team_bonus_records')->whereRaw("date_format(date,'%Y-%m-%d')>'2018-11-21'")->delete();
            }

            DB::table('team_bonuses')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            DB::table('team_person_rewards')->whereRaw("date_format(get_date,'%Y-%m-%d')>='$date' and belong<>'system'")->delete();

            DB::table('team_person_future_rewards')->whereRaw("date_format(get_date,'%Y-%m-%d')>='$date'")
                ->update(['status' => 0, 'updated_at' => $now]);

            DB::commit();
            echo "回滚执行成功\n";
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error("出错：" . $e->getMessage());
        }
    }
}
