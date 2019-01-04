<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/5
 * Time: 上午10:01
 */

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PersonRewardExport extends AbstractExport implements ShouldAutoSize
{
    protected $header_num;
    protected $data;

    public function __construct($request)
    {
        $this->startDate = Carbon::parse($request->start_date)->timezone('PRC')->toDateString();
        $this->endDate = Carbon::parse($request->end_date)->timezone('PRC')->toDateString();
        $this->fileName = '星视度智造团队奖励';
    }

    public function collection()
    {
        $team_project_rate = DB::table('team_project_members as tpm')
            ->join("team_projects as tp", "tp.id", "=", "tpm.team_project_id")
            ->whereRaw("tpm.type in ('originality','plan','animation')")
            ->whereRaw("date_format(tp.launch_date, '%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->groupBy("tp.belong")
            ->selectRaw("tp.belong,sum(rate) as 'rate_total'");

        //member_program_num
        //0不计入 1基础条目 2简单条目 3 通用节目 4项目
        $member_program_num = DB::table("team_project_members as tpm")
            ->join("team_projects as tp", "tp.id", "=", "tpm.team_project_id")
            ->join(DB::raw("({$team_project_rate->toSql()}) as rate_total"), 'rate_total.belong', '=', 'tp.belong')
            ->whereRaw("tpm.type in ('originality','plan','animation')")
            ->whereRaw("date_format(tp.launch_date, '%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->groupBy("tpm.user_id", "tpm.user_name")
            ->selectRaw("tpm.user_id,tpm.user_name,
	           round(sum( (case tp.project_attribute 
    			    when 1 then 1 
    			    when 2 then 0.1
    			    else 0 
    		        end)*(tpm.rate/rate_total.rate_total) ),2)as item_num,
    	       round(sum((case tp.project_attribute 
    			    when 3 then 1
    			    else 0 
   		 	        end)*(tpm.rate/rate_total.rate_total)),2)as program_num,
   		       round(sum((case project_attribute
    		        when 4 then 1
    		        else 0 
   		 	        end)*(tpm.rate/rate_total.rate_total)),2) as project_num
   		 	         ");

        $startMonth = Carbon::parse($this->startDate)->timezone('PRC')->format("Y-m");
        $endMonth = Carbon::parse($this->endDate)->timezone('PRC')->format("Y-m");

        $header1 = ["用户ID", "用户名", "条目数量", "节目数量", "项目数量", "累计节目数量"];
        $selectRaw = "tpr.user_id,users.name as 'user_name',";
        for ($temp_month = $startMonth; $temp_month <= $endMonth; $temp_month = Carbon::parse($temp_month)->addMonth()->format("Y-m")) {
            $header1[] = "体验绩效_" . $temp_month;
            $selectRaw .= " round(sum(case date_format(tpr.date,'%Y-%m') when '" . $temp_month . "' then tpr.experience_money else 0 end ),2) as '体验绩效_" . $temp_month . "',";
        }
        $header1 = array_merge($header1, ["体验绩效总计", "平台奖"]);
        $selectRaw .= " round(sum(experience_money),2) as 'experience_money',round(sum(system_money),2) as 'system_money'";

        $member_money = DB::table("team_person_rewards as tpr")
            ->join('users', 'tpr.user_id', '=', 'users.id')
            ->join("team_projects as tp", "tp.belong", "=", "tpr.belong")
            ->whereRaw("date_format(tp.launch_date, '%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->whereRaw("date_format(tpr.date, '%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->whereRaw("tpr.type in ('originality','plan','animation')")
            ->groupBy("tpr.user_id")
            ->selectRaw($selectRaw);

        //汇总
        $totalData = DB::table(DB::raw("({$member_program_num->toSql()}) as V1"))
            ->leftJoin(DB::raw("({$member_money->toSql()}) as V2"), 'V1.user_id', '=', 'V2.user_id')
            ->selectRaw("V1.*,round((V1.item_num/2+V1.program_num+V1.project_num*2),3) as 'average_program',V2.* ")
            ->get()->map(function ($item) {
                return (array)$item;
            })->toArray();

        $header2 = [];
        foreach ($header1 as $header) {
            $header2[] = '';
        }

        $data = collect(array_merge([$header1, $header2], $totalData));

        $this->header_num = count($header1) - 1;
        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $cellArray = [];
                for ($i = 0; $i <= $this->header_num; $i++) {
                    $cellArray[] = $this->change($i) . '1:' . $this->change($i) . '2';
                }
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:' . $this->change($this->header_num) . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change($this->header_num) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change($this->header_num) . '2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()->freezePane('A3');
            }
        ];
    }

    public function change($x)
    {
        $map = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $t = "";
        while ($x >= 0) {
            $t = $map[$x % 26] . $t;
            $x = floor($x / 26) - 1;
        }
        return $t;
    }
}