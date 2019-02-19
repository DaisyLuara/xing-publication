<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
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
        $types_str = '';
        foreach (TeamPersonReward::$mainMapping as $key => $value) {
            $types_str .= $key . ":" . $value . '; ';
        }

        $type = $this->ask("输入需要回滚的类型(" . $types_str . " )：");

        if ($type == TeamPersonReward::MAIN_TYPE_CPE) {
            $date = Carbon::parse($this->ask("输入回滚日期(eg: 2018-01-01)："))->toDateString();
            if ($date < '2017-04-21' || $date >= Carbon::now()->toDateString()) {
                return $this->error('时间输入有误！');
            }

            $now = date("Y-m-d H:i:s");
            DB::beginTransaction();
            try {
                if ($date >= '2018-11-21') {
                    DB::table('team_bonus_records')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
                    DB::table('team_bonus_records')->insert(['date' => $date, 'created_at' => $now]);
                } else {
                    DB::table('team_bonus_records')->whereRaw("date_format(date,'%Y-%m-%d')>'2018-11-21'")->delete();
                }

                DB::table('team_bonuses')->whereRaw("date_format(date,'%Y-%m-%d')>='$date'")->delete();
                DB::table('team_person_rewards')->whereRaw("main_type = '" . $type . "' and date_format(get_date,'%Y-%m-%d')>='$date'")->delete();

                DB::table('team_person_future_rewards')->whereRaw("main_type = '" . $type . "' and date_format(date,'%Y-%m-%d')>='$date'")->delete();
                DB::table('team_person_future_rewards')->whereRaw("main_type = '" . $type . "' and date_format(get_date,'%Y-%m-%d')>='$date'")
                    ->update(['status' => 0, 'updated_at' => $now]);

                DB::commit();
                echo $type . "回滚执行成功\n";
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error("出错：" . $e->getMessage());
            }
        } else if ($type == TeamPersonReward::MAIN_TYPE_PBI) {
            DB::beginTransaction();
            try {
                $rollback_type = $this->ask($type . "输入绩效回滚方式：1-根据时间回滚(默认) 2-根据收款合同ID回滚 ：");
                if($rollback_type == 2){
                    $contract_id = $this->ask("输入回滚的合同ID:");
                    Contract::query()->findOrFail($contract_id);

                    DB::table('contracts')->where('id','=',$contract_id)
                        ->update(['pbi_money'=>null,'pbi_date'=>null]);

                    DB::table('team_person_rewards as tpr')
                        ->leftJoin('team_projects as tp','tpr.belong',"=","tp.belong")
                        ->whereRaw("tpr.main_type = '" . $type . "' and tp.contract_id = ". $contract_id)->delete();

                    DB::commit();
                    echo $type . "根据收款合同ID 回滚执行成功\n";
                } else{
                    $date_time = Carbon::parse($this->ask("输入回滚日期(eg: 2018-01-01 12:00:00)："))->toDateTimeString();

                    DB::table('contracts')->where('pbi_date','>=',$date_time)
                        ->update(['pbi_money'=>null,'pbi_date'=>null]);

                    DB::table('team_person_rewards')
                        ->whereRaw("main_type = '" . $type . "' and get_date >= '". $date_time."'")->delete();
                    DB::commit();
                    echo $type . "根据时间 回滚执行成功\n";
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error("出错：" . $e->getMessage());
            }
        } else {
            echo "不存在该种绩效类型\n";
        }


    }
}
