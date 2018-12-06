<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/5
 * Time: 上午10:01
 */

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use DB;

class PersonRewardExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->fileName = '星视度智造团队奖励';
    }

    public function collection()
    {
        //人员统计
        $member = DB::table("team_person_rewards as tpr")
            ->join('users', 'tpr.user_id', '=', 'users.id')
            ->whereRaw("date_format(date, '%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->groupBy(DB::raw("user_id"))
            ->selectRaw("user_id,users.name as username");

        //个人节目统计
        $projectSql = DB::table('team_person_rewards as tpr')
            ->join('team_projects as tp', 'tpr.belong', '=', 'tp.belong')
            ->whereRaw("date_format(launch_date, '%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->groupBy(DB::raw("user_id, tpr.belong"))
            ->selectRaw("user_id,tpr.belong, project_attribute");
        $project = DB::table(DB::raw("({$projectSql->toSql()}) as b"))
            ->groupBy(DB::raw("user_id"))
            ->selectRaw("user_id,count(project_attribute = 2 or project_attribute = 3 or null) as projectnum,count(project_attribute = 1 or null) itemnum");

        //体验绩效统计
        $groupDate = DB::table('team_person_rewards as tpr')->join('team_projects as tp', 'tpr.belong', '=', 'tp.belong')
            ->whereRaw("date_format(date,'%Y-%m-%d') between '$this->startDate' and '$this->endDate' ")
            ->selectRaw("date_format(min(launch_date),'%Y-%m') as startDate,date_format(max(launch_date),'%Y-%m') as endDate")
            ->first();
        $startDate = $groupDate->startDate;
        $endDate = $groupDate->endDate;
        $dates = [$startDate];
        while ((new Carbon($endDate))->gt(new Carbon($startDate))) {
            $startDate = date_format((new Carbon($startDate))->addMonth(1), 'Y-m');
            $dates[] = $startDate;
        }
        $this->datenum = count($dates);
        $sql = DB::table('team_person_rewards as tpr')->join('team_projects as tp', 'tpr.belong', '=', 'tp.belong')
            ->whereRaw("date_format(date,'%Y-%m-%d') between '$this->startDate' and '$this->endDate' and tpr.belong<>'system'")
            ->groupBy(DB::raw("user_id,date_format(launch_date,'%Y-%m')"))
            ->selectRaw("user_id, sum(experience_money) as experience_money, date_format(launch_date, '%Y-%m') as date");
        $Max = "";
        for ($i = 0; $i < sizeof($dates); $i++) {
            $Max = $Max . ",max(case a.date when '$dates[$i]' then experience_money else 0 end) '$dates[$i]'";
        }
        $personReward = DB::table(DB::raw("({$sql->toSql()}) as a"))
            ->groupBy(DB::raw("user_id"))
            ->selectRaw("user_id" . $Max);

        //平台奖励统计
        $system = DB::table('team_person_rewards as tpr')
            ->whereRaw("date_format(date, '%Y-%m-%d') between '$this->startDate' and '$this->endDate' and belong = 'system'")
            ->groupBy(DB::raw("user_id"))
            ->selectRaw("user_id, sum(system_money) as system_money");

        //汇总
        $totalData = DB::table(DB::raw("({$member->toSql()}) as a"))
            ->join(DB::raw("({$project->toSql()}) as b"), function ($join) {
                $join->on('a.user_id', '=', 'b.user_id');
            }, null, null, 'left')
            ->join(DB::raw("({$personReward->toSql()}) as c"), function ($join) {
                $join->on('a.user_id', '=', 'c.user_id');
            }, null, null, 'left')
            ->join(DB::raw("({$system->toSql()}) as d"), function ($join) {
                $join->on('a.user_id', '=', 'd.user_id');
            }, null, null, 'left')
            ->selectRaw("username,ifnull(projectnum,0) as projectnum,ifnull(itemnum,0) as itemnum,c.*,d.system_money")
            ->get();
        $data = collect();
        $header1 = ['姓名', '节目', '条目', '折算节目数量'];
        for ($i = 0; $i < count($dates); $i++) {
            $header1 = array_merge($header1, ['体验奖' . $dates[$i]]);
        }
        $header1 = array_merge($header1, ['体验奖总计', '平台奖']);
        $header2 = ['', '', '', '', '', ''];
        for ($i = 0; $i < count($dates); $i++) {
            $header2 = array_merge($header2, ['']);
        }
        $data->push($header1);
        $data->push($header2);

        $totalData->each(function ($item) use (&$data, $dates) {

            $item = json_decode(json_encode($item), true);
            $aa = [
                'user_name' => $item['username'],
                'project_num' => $item['projectnum'],
                'item_num' => $item['itemnum'],
                'average_project' => $item['projectnum'] + $item['itemnum'] / 2,
            ];
            $total = 0;
            for ($i = 0; $i < count($dates); $i++) {
                $aa[$dates[$i]] = $item[$dates[$i]] ? $item[$dates[$i]] : 0;
                $total = $total + $item[$dates[$i]];
            }
            $aa['total'] = $total;
            $aa['system_monye'] = $item['system_money']?$item['system_money']:0;
            $data->push($aa);
        });
        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray =
                    [
                        'A1:A2', 'B1:B2', 'C1:C2', 'D1:D2',
                        $this->change(4 + $this->datenum) . '1:' . $this->change(4 + $this->datenum) . '2',
                        $this->change(5 + $this->datenum) . '1:' . $this->change(5 + $this->datenum) . '2'
                    ];
                for ($i = 0; $i < $this->datenum; $i++) {
                    $num = 4 + $i;
                    $cellArray[] = $this->change($num) . '1:' . $this->change($num) . '2';
                }
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change((5 + $this->datenum)) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change((5 + $this->datenum)) . '2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change((5 + $this->datenum)) . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
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