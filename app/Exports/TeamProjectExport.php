<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/12
 * Time: 下午5:43
 */

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class TeamProjectExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->start_date_begin = $request->start_date_begin;
        $this->end_date_begin = $request->end_date_begin;

        $this->start_date_online = $request->start_date_online;
        $this->end_date_online = $request->end_date_online;

        $this->start_date_launch = $request->start_date_launch;
        $this->end_date_launch = $request->end_date_launch;

        $this->alias = $request->alias;
        $this->status = $request->status;
        $this->fileName = '团队节目人员配置信息';
    }

    public function collection()
    {
        $typeMapping = [
            'interaction' => '交互技术',
            'originality' => '节目创意',
            'h5' => 'H5开发',
            'animation' => '设计动画',
            'plan' => '节目统筹',
            'tester' => '节目测试',
            'operation' => '平台运营'
        ];
        $projectAttribute = [
            '1' => '基础条目',
            '2' => '通用节目',
            '3' => '定制节目',
            '4' => '定制项目'
        ];
        $sql = DB::table('team_projects as tp')->leftJoin('team_project_members as tpm', 'tp.id', '=', 'tpm.team_project_id')
            ->join('users', 'tp.applicant', '=', 'users.id')
            ->where(function ($q) {
                if ($this->alias) {
                    $q->whereRaw("belong='$this->alias'");
                }
                if ($this->status) {
                    $q->whereRaw("status='$this->status'");
                }
                if ($this->start_date_begin && $this->end_date_begin) {
                    $q->whereRaw("begin_date between '$this->start_date_begin' and '$this->end_date_begin'");
                }
                if ($this->start_date_online && $this->end_date_online) {
                    $q->whereRaw("online_date between '$this->start_date_online' and '$this->end_date_online'");
                }
                if ($this->start_date_launch && $this->end_date_launch) {
                    $q->whereRaw("launch_date between '$this->start_date_launch' and '$this->end_date_launch'");
                }
            })
            ->selectRaw("users.name as applicant,tp.type as project_type,project_name,online_date,launch_date,project_attribute,link_attribute,xo_attribute,h5_attribute,art_innovate,dynamic_innovate,interact_innovate,remark,tpm.type as type,group_concat(concat(user_name, ':', rate)) as username")
            ->groupBy(DB::raw("belong,tpm.type"));
        $case = "";
        foreach ($typeMapping as $key => $value) {
            $case = $case . ",max(case type when '$key' then username else null end) '$key'";
        }
        $project = DB::table(DB::raw("({$sql->toSql()}) as a"))
            ->selectRaw("applicant,project_type,project_name,online_date,launch_date,project_attribute,link_attribute,xo_attribute,h5_attribute,art_innovate,dynamic_innovate,interact_innovate,remark" . $case)
            ->groupBy("project_name")
            ->get();
        $header1 = ['申请人', '节目类型', '节目名称', '上线时间', '投放时间', '节目属性', '联动属性', '小偶属性', 'H5属性', '艺术风格创新点', '动效体验创新点', '交互技术创新点'];
        foreach ($typeMapping as $item) {
            $header1[] = $item;
        }
        $header1[] = "备注";
        $header2 = ['', '', '', '', '', '', '', '', '', '', '', '', ''];
        foreach ($typeMapping as $item) {
            $header2[] = '';
        }
        $data = collect();
        $data->push($header1);
        $data->push($header2);
        $project->each(function ($item) use (&$data, $typeMapping, $projectAttribute) {
            $aa = [
                'applicant' => $item->applicant,
                'project_type' => $item->project_type == 1 ? '提前' : '正常',
                'project_name' => $item->project_name,
                'online_date' => $item->online_date,
                'launch_date' => $item->launch_date,
                'project_attribute' => $projectAttribute[$item->project_attribute],
                'link_attribute' => $item->link_attribute == 1 ? '是' : '否',
                'xo_attribute' => $item->xo_attribute == 1 ? '是' : '否',
                'h5_attribute' => $item->h5_attribute == 1 ? '基础' : '复杂',
                'art_innovate' => $item->art_innovate,
                'dynamic_innovate' => $item->dynamic_innovate,
                'interact_innovate' => $item->interact_innovate
            ];
            foreach ($typeMapping as $key => $value) {
                $aa[$key] = $item->$key;
            }
            $aa['remark'] = $item->remark;
            $data->push($aa);
        });
        $this->header = $header1;
        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                for ($i = 0; $i < count($this->header); $i++) {
                    $cellArray[] = $this->change($i) . '1:' . $this->change($i) . '2';
                }
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()
                    ->getStyle('A1:T' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:T2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:T' . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);
                $event->sheet->getDelegate()->freezePane('A1');
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