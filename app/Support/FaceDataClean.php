<?php

use App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord;
use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 绩效清洗
 * @return string
 */
function teamBonusClean()
{
    $main_type = TeamPersonReward::MAIN_TYPE_CPE;
    $date = TeamBonusRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        //更新publication项目的投放时间
        $projectList = DB::connection('ar')->table('ar_product_list')
            ->whereRaw("online<>0")
            ->selectRaw("versionname,online")
            ->get();
        foreach ($projectList as $item) {
            TeamProject::query()->where('belong', $item->versionname)->update(['launch_date' => date('Y-m-d', $item->online / 1000)]);
        }

        //符合要求的点位当日的数据
        $faceCount1 = DB::connection('ar')->table('xs_face_count_log as fcl')
            ->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff', 'ao.bd_z', '=', 'admin_staff.z')
            ->whereRaw("date_format(fcl.date, '%Y-%m-%d')='$date' and apl.online<>0 and fcl.oid not in ('16', '19', '30', '31', '177','182','327','328','329','334','335','540') and aom.marketid <> '15' and aos.name<>'EXE颜镜店' and aos.name<>'星视度研发' and admin_staff.realname<>'颜镜店'")
            ->groupBy(DB::raw("date_format(fcl.date, '%Y-%m-%d'),fcl.oid,fcl.belong"))
            ->orderBy('date')
            ->orderBy('apl.id')
            ->orderBy('looknum', 'desc')
            ->selectRaw("date_format(fcl.date, '%Y-%m-%d') as date,apl.name as name,apl.online as online,fcl.belong as belong,sum(playernum7)as playernum7,sum(playernum15) as playernum15 ,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum");

        //每个节目每天的前100个点位数据汇总
        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount1->toSql()}) v"))
            ->selectRaw("@date:=date date,@name := name name,online,belong,sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum")
            ->whereRaw("(@gn :=(case when (@date = date and @name = name) then @gn + 1 else 1 end)) <= 100")
            ->groupBy('name')
            ->get();

        $count = [];
        foreach ($faceCount as $item) {
            //数据奖金池 B  $totalMoney
            $player7Money = $item->playernum7 * 0.01;
            $player15Money = $item->playernum15 * 0.02;
            $player21Money = $item->playernum21 * 0.05;
            $uCPAMoney = $item->omo_outnum * 0.2;
            $totalMoney = round($player7Money + $player15Money + $player21Money + $uCPAMoney, 2);

            //节目的投放日期
            $launchDate = date('Y-m-d', $item->online / 1000);

            $teamProject = TeamProject::query()->where('belong', $item->belong)->first();
            //投放时长 当前日期-投放日期
            $launchTime = $date >= $launchDate ? (new Carbon($date))->diffInDays($launchDate) : 1000;

            //新颖性系数T $factor
            $factor = 0;
            if ($teamProject) {
                if ($launchTime <= 30) {
                    //主管确认
                    if ($teamProject->status == 4) {
                        //提前制作时间 投放时间-上线时间
                        $advanceTime = $launchDate >= Carbon::parse($teamProject->online_date)->toDateString() ? (new Carbon($launchDate))->diffInDays($teamProject->online_date) : 0;
                        if ($advanceTime >= 90) {
                            $factor = 1.2;
                        }
                        if ($advanceTime >= 60 && $advanceTime < 90) {
                            $factor = 1.1;
                        }
                        if ($advanceTime < 60) {
                            if ($teamProject->individual_attribute) {
                                $factor = 1;
                            } else {
                                $factor = 0.9;
                            }
                        }
                    }
                    //运营确认
                    if ($teamProject->status == 3 && $teamProject->type == 0) {
                        if ($teamProject->individual_attribute) {
                            $factor = 1;
                        } else {
                            $factor = 0.9;
                        }
                    }
                }
                //已投放一个月
                if ($launchTime > 30 && $launchTime <= 60) {
                    $factor = 0.8;
                }
                //已投放两个月
                if ($launchTime > 60 && $launchTime <= 90) {
                    $factor = 0.7;
                }
                //已投放三个月
                if ($launchTime > 90 && $launchTime <= 120) {
                    $factor = 0.6;
                }
                //已投放四个月
                if ($launchTime > 120 && $launchTime <= 150) {
                    $factor = 0.5;
                }
                //已投放五个月
                if ($launchTime > 150 && $launchTime <= 180) {
                    $factor = 0.4;
                }
                //已投放六个月
                if ($launchTime > 210 && $launchTime <= 240) {
                    $factor = 0.3;
                }
                //已投放七个月
                if ($launchTime > 240 && $launchTime <= 270) {
                    $factor = 0.2;
                }
                //已投放八个月
                if ($launchTime > 270 && $launchTime <= 300) {
                    $factor = 0.1;
                }

            }

            if ($totalMoney > 0 && $factor > 0) {
                $count[] = [
                    'project_name' => $item->name,
                    'belong' => $item->belong,
                    'money' => $totalMoney, //B
                    'factor' => $factor,  //T
                    'date' => $date
                ];
            }
        }

        try {
            DB::beginTransaction();
            $result = [];

            $result[] = DB::table('team_bonuses')->insert($count);

            $data_copyright = DB::table('team_projects as tp')
                ->join('team_projects as tp2', 'tp.copyright_project_id', '=', 'tp2.id')
                ->join('team_project_members as tpm', 'tp2.id', '=', 'tpm.team_project_id')
                ->join('team_bonuses as tb', 'tp.belong', '=', 'tb.belong')
                ->leftjoin('contracts', 'contracts.id', '=', 'tp.contract_id')
                ->whereRaw("date_format(tb.date,'%Y-%m-%d')='$date' 
                and IFNull(contracts.amount,0) <= 0
                and tpm.type not in ('operation_quality','tester_quality')")
                ->selectRaw("tpm.user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
                money * 0.2 as 'money',factor,rate,concat(tpm.type,'|copyright') as type");

            $data = DB::table('team_projects as tp')
                ->join('team_project_members as tpm', 'tp.id', '=', 'tpm.team_project_id')
                ->join('team_bonuses as tb', 'tp.belong', '=', 'tb.belong')
                ->leftjoin('contracts', 'contracts.id', '=', 'tp.contract_id')
                ->whereRaw("date_format(tb.date,'%Y-%m-%d')='$date'")
                ->selectRaw("user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
                case  when IFNull(contracts.amount,0) <= 0 and tp.copyright_project_id > 0  then money * 0.8 else money end as 'money',
                factor,rate,tpm.type as type")
                ->unionAll($data_copyright)
                ->get();

            $rewards = [];
            $rewards_future = [];
            $date_future = Carbon::parse($date)->addMonth(3)->startOfMonth()->toDateString();

            $now = Carbon::now('PRC')->toDateTimeString();
            foreach ($data as $item) {
                if($item->rate <= 0){
                    continue;
                }
                if (in_array($item->type, ['tester_quality', 'operation_quality'])) {
                    $rewards_future[] = [
                        'user_id' => $item->user_id,
                        'project_name' => $item->project_name,
                        'belong' => $item->belong,
                        'type' => $item->type,
                        'main_type' => $main_type,
                        'total' => round($item->money * $item->factor * $item->rate, 6),
                        'date' => $date,
                        'get_date' => $date_future,
                        'status' => 0,
                        'team_project_id' => $item->team_project_id,
                        'created_at' => $now
                    ];
                } else {
                    $rewards[] = [
                        'user_id' => $item->user_id,
                        'project_name' => $item->project_name,
                        'belong' => $item->belong,
                        'type' => $item->type,
                        'main_type' => $main_type,
                        'total' => round($item->money * $item->factor * $item->rate, 6),
                        'date' => $date,
                        'get_date' => $date,
                    ];
                }
            }

            $result[] = DB::table('team_person_rewards')->insert($rewards);
            $result[] = DB::table('team_person_future_rewards')->insert($rewards_future);

            //判断当前日期是否是月份第一天，每个月份n第一天需要发第n-3个月的冻结奖金
            if ($date == Carbon::parse($date)->startOfMonth()->toDateString()) {

                //得到不同测试/运营的人，在本季度第一天的统计，取消扣除的奖金
                $quarterDate = Carbon::parse($date)->startOfQuarter()->toDateString();

                echo "quarterDate========" . $quarterDate;

                $users_bugs = DB::table('team_project_bug_records')
                    ->where('date', $quarterDate)->groupby("user_id", "duty")
                    ->selectRaw("user_id,duty,sum(bug_num) as num")
                    ->get();

                foreach ($users_bugs as $user_bug) {
                    if ($user_bug->num == 1) {
                        $start_date = Carbon::parse($quarterDate)->subMonths(3)->toDateString();
                        $end_date = Carbon::parse($quarterDate)->subMonths(2)->toDateString();
                    } else if ($user_bug->num == 2) {
                        $start_date = Carbon::parse($quarterDate)->subMonths(3)->toDateString();
                        $end_date = Carbon::parse($quarterDate)->subMonths(1)->toDateString();
                    } else if ($user_bug->num >= 3) {
                        $start_date = Carbon::parse($quarterDate)->subMonths(3)->toDateString();
                        $end_date = $quarterDate;
                    } else {
                        continue;
                    }

                    DB::table("team_person_future_rewards")
                        ->where('user_id', '=', $user_bug->user_id)
                        ->where('date', '>=', $start_date)
                        ->where('date', '<', $end_date)
                        ->where('type', '=', $user_bug->duty)
                        ->where('main_type', '=', $main_type)
                        ->where('status', '=', 0)
                        ->update(['status' => -1, 'updated_at' => $now]);
                }

                //发放当前需发放的rewards
                $future_rewards = DB::table("team_person_future_rewards")
                    ->where('get_date', '=', $date)
                    ->where('main_type', '=', $main_type)
                    ->where('status', '=', 0);

                $future_rewards_array = $future_rewards
                    ->selectRaw("user_id,project_name,belong,type,main_type,total,date,get_date")
                    ->get()->map(function ($value) {
                        return (array)$value;
                    })->toArray();
                $result[] = DB::table('team_person_rewards')->insert($future_rewards_array);

                $future_rewards->update(['status' => 1, 'updated_at' => $now]);
            }


            if (check_arr($result)) {
                echo "执行至" . $date . "\n";
                DB::commit();
                $date = (new Carbon($date))->addDay(1)->toDateString();
                TeamBonusRecord::create(['date' => $date]);
            } else {
                DB::rollBack();
                echo "执行至" . $date . ':fail' . json_encode($result) . "\n";
                return false;
            }
        } catch (Exception $e) {
            DB::rollBack();
            echo "执行至" . $date . ':出错，' . $e->getMessage() . "\n";
            return false;
        }
    }

    return true;
}

/**
 * PBI 绩效奖金清洗
 */
function PBIBonusClean()
{
    $main_type = TeamPersonReward::MAIN_TYPE_PBI;

    //查询符合条件的合同ID
    //1 收款合同 type = 0 ;
    //2 合同状态为 3|4 已审批|特批
    //3 pbi_money 为null
    $contract_ids = DB::table('contracts')
        ->whereRaw("type = 0 and status in (3,4) and pbi_money is null and amount > 0")
        ->pluck('id')->toArray();

    if (!$contract_ids) {
        echo "没有符合条件的合同\n";
        exit;
    }

    //符合要求合同的收款金额
    $contract_receipt = DB::table('contract_receive_dates as crd')
        ->leftJoin('invoice_receipts as ir', 'ir.id', '=', 'crd.invoice_receipt_id')
        ->leftJoin('contracts as ct', 'ct.id', '=', 'crd.contract_id')
        ->whereRaw('crd.receive_status = 1 and ir.claim_status = 1 and contract_id in (' . implode(',', $contract_ids) . ')')
        ->groupBy('crd.contract_id')
        ->selectRaw(" crd.contract_id,sum(ir.receipt_money) as 'receipt_money',ct.contract_number,ct.name,ct.special_num,ct.common_num,ct.amount");

    //符合要求合同的花费金额
    $contract_cost = DB::table('contract_costs as cc ')
        ->whereRaw("contract_id in (" . implode(',', $contract_ids) . ")")
        ->groupBy('contract_id')
        ->selectRaw("contract_id,sum(confirm_cost) as cost");


    //得到（合同总金额 amount <= 收款金额总和）合同，以及 节目制造营收JS值
    $contracts_with_pbi_money = DB::table(DB::raw("({$contract_receipt->toSql()}) V1"))
        ->leftJoin(DB::raw("({$contract_cost->toSql()}) V2"), 'V1.contract_id', '=', 'V2.contract_id')
        ->whereRaw(" V1.amount <= V1.receipt_money ")
        ->selectRaw("V1.* , IfNull(V2.cost,0) as 'cost' , (V1.receipt_money - IFNULL(V2.cost,0)) as 'pbi_money',
            (case when (common_num + special_num) > 0 
	          	    then round((V1.receipt_money - IFNULL(V2.cost,0) ) / (special_num + common_num) * (case when common_num > 0 then 0.8 else 1 end),6)
	            else 0
	        end) 
	        as 'special_JS',
	        
	        (case when common_num > 0 
	          	    then round((V1.receipt_money - IFNULL(V2.cost,0) ) / (special_num + common_num) * 0.2 , 6)
	            else 0
	        end) 
	        as 'common_JS'
            ")
        ->get();

    foreach ($contracts_with_pbi_money->toArray() as $contract_with_pbi_money) {
        DB::beginTransaction();
        try {
            // 查询出符合条件的某合同的 未完成节目数量。
            $undone_project_num = DB::table('team_projects as tp ')
                ->whereRaw("contract_id = " . $contract_with_pbi_money->contract_id . " and case type when 1 then status != 4 else status != 3 end")
                ->selectRaw("count(*) as num")
                ->value("num");
            // 如果节目存在未完成的，则不进行发放
            if ($undone_project_num > 0) {
                continue;
            }

            //查询出该合同的所有相关节目（不同节目类型，不同的JS）,原创团队与现团队的PBI
            $data_copyright = DB::table('team_projects as tp')
                ->join('team_project_members as tpm', 'tp.copyright_project_id', '=', 'tpm.team_project_id')
                ->whereRaw("tp.contract_id = " . $contract_with_pbi_money->contract_id . " and tpm.type not in ('" . implode("','", TeamProjectMember::$team_quality) . "')")
                ->selectRaw("tpm.user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
	            case tp.individual_attribute when 1 then " . $contract_with_pbi_money->special_JS . " when 2 then " . $contract_with_pbi_money->common_JS . " else null end as 'JS',
	            case 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_zhizao) . "') then 0.25 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_it) . "') then 0.125
	            	else 0 
 	            end as 'js_rate',
 	            0.2 as 'copyright_rate',
	            tpm.rate,concat(tpm.type,'|copyright') as type");

            $data = DB::table('team_projects as tp')
                ->join('team_project_members as tpm', 'tp.id', '=', 'tpm.team_project_id')
                ->whereRaw("tp.contract_id = " . $contract_with_pbi_money->contract_id . " and tpm.type not in ('" . implode("','", TeamProjectMember::$team_quality) . "')")
                ->selectRaw("tpm.user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
	            case tp.individual_attribute when 1 then " . $contract_with_pbi_money->special_JS . " when 2 then " . $contract_with_pbi_money->common_JS . " else null end as 'JS',
	            case 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_zhizao) . "') then 0.25 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_it) . "') then 0.125
	            	else 0 
 	            end as 'js_rate',
 	            case when (tp.copyright_project_id is not null) then 0.8 else 1 end as 'copyright_rate',
	            tpm.rate,tpm.type as type")
                ->unionAll($data_copyright)
                ->get();

            $now = Carbon::now('PRC')->toDateTimeString();
            $rewards = [];
            foreach ($data as $item) {
                $total = round($item->JS * $item->js_rate * $item->copyright_rate * $item->rate, 6);
                if ($total > 0) {
                    $rewards[] = [
                        'user_id' => $item->user_id,
                        'project_name' => $item->project_name,
                        'belong' => $item->belong,
                        'type' => $item->type,
                        'main_type' => $main_type,
                        'total' => $total,
                        'date' => $now,
                        'get_date' => $now
                    ];
                }
            }
            DB::table('team_person_rewards')->insert($rewards);
            //修改改合同的状态
            DB::table('contracts')->where('id', '=', $contract_with_pbi_money->contract_id)
                ->update(['pbi_money' => $contract_with_pbi_money->pbi_money, 'pbi_date' => $now]);

            DB::commit();
            echo "PBI 绩效执行完成！";
            exit;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            exit;
        }
    }

}