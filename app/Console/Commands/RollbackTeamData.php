<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord;
use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

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
        $date = $this->ask("输入回滚时间：");
        if ($date < '2017-04-21' || $date >= Carbon::now()->toDateString()) {
            return $this->error('时间输入有误！');
        }

        if ($date >= '2018-11-21') {
            TeamBonusRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
            TeamBonusRecord::create(['date' => $date]);
        } else {
            TeamBonusRecord::query()->whereRaw("date_format(date,'%Y-%m-%d')>'2018-11-21'");
        }
        DB::table('team_bonuses')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
        DB::table('team_person_rewards')->whereRaw("date_format(date,'%Y-%m-%d')>='$date' and belong<>'system'")->delete();
    }
}
