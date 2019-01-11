<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/12
 * Time: 下午5:43
 */

namespace App\Exports;

use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class TeamProjectExport extends AbstractExport implements ShouldAutoSize
{
    protected $header;
    protected $data;

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
        $typeMapping = TeamPersonReward::$typeMapping;
        $projectAttributeMapping = TeamProject::$projectAttributeMapping;
        $statusMapping = TeamProject::$statusMapping;
        $interactionAttributeMapping = TeamProject::$interactionAttributeMapping;

        $sql = DB::table('team_projects as tp')
            ->leftJoin('team_project_members as tpm', 'tp.id', '=', 'tpm.team_project_id')
            ->leftJoin('contracts', 'tp.contract_id', '=', 'contracts.id')
            ->leftJoin('team_projects as tp2', 'tp2.id', '=', 'tp.copyright_project_id')
            ->join('users', 'tp.applicant', '=', 'users.id')
            ->where(function ($q) {
                if ($this->alias) {
                    $q->whereRaw("tp.belong='$this->alias'");
                }
                if ($this->status) {
                    $q->whereRaw("tp.status='$this->status'");
                }
                if ($this->start_date_begin && $this->end_date_begin) {
                    $q->whereRaw("tp.begin_date between '$this->start_date_begin' and '$this->end_date_begin'");
                }
                if ($this->start_date_online && $this->end_date_online) {
                    $q->whereRaw("tp.online_date between '$this->start_date_online' and '$this->end_date_online'");
                }
                if ($this->start_date_launch && $this->end_date_launch) {
                    $q->whereRaw("tp.launch_date between '$this->start_date_launch' and '$this->end_date_launch'");
                }
            })
            ->selectRaw("
            users.name as applicant,
            tp.type as project_type,
            tp.project_name,IFNULL(tp2.project_name,'无') as copyright_project,tp.status,tp.online_date,tp.launch_date,
            tp.project_attribute,tp.link_attribute,tp.h5_attribute,
            tp.interaction_attribute,tp.hidol_attribute,
            tp.individual_attribute,contracts.contract_number,contracts.amount,
            tp.art_innovate,tp.dynamic_innovate,tp.interact_innovate,
            tp.remark,tp.test_remark,tpm.type as type,group_concat(concat(tpm.user_name, ':', rate)) as username")
            ->groupBy(DB::raw("tp.belong,tpm.type"));


        $case = "";
        foreach ($typeMapping as $key => $value) {
            $case = $case . ",max(case type when '$key' then username else null end) '$key'";
        }

        $project = DB::table(DB::raw("({$sql->toSql()}) as a"))
            ->selectRaw("applicant,
            project_type,project_name,copyright_project,status,online_date,launch_date,
            project_attribute,link_attribute,h5_attribute,
            interaction_attribute,hidol_attribute,
            individual_attribute,contract_number,amount,
            art_innovate,dynamic_innovate,interact_innovate,remark,test_remark" . $case)
            ->groupBy("project_name")
            ->get()->map(function ($item) use ($typeMapping, $statusMapping, $projectAttributeMapping, $interactionAttributeMapping) {
                $interaction_attribute_text = Collect(explode(',', $item->interaction_attribute))
                    ->map(function ($v) use ($interactionAttributeMapping) {
                        return $interactionAttributeMapping[$v] ?? "--";
                    })->toArray();
                $result = [
                    'applicant' => $item->applicant,
                    'project_type' => $item->project_type == 1 ? '提前' : '正常',
                    'project_name' => $item->project_name,
                    'copyright_project'=>$item->copyright_project,
                    'status' => $statusMapping[$item->status],
                    'online_date' => $item->online_date,
                    'launch_date' => $item->launch_date,
                    'project_attribute' => $projectAttributeMapping[$item->project_attribute],
                    'link_attribute' => $item->link_attribute == 1 ? '是' : '否',
                    'h5_attribute' => $item->h5_attribute == 1 ? '基础' : '复杂',
                    'interaction_attribute' => implode(',', $interaction_attribute_text),
                    'hidol_attribute' => $item->hidol_attribute == 1 ? '是' : '否',
                    'individual_attribute' => $item->individual_attribute == 1 ? '是' : '否',
                    'contract_number' => "\t" . $item->contract_number . "\t",
                    'contract_amount' => $item->amount,
                    'art_innovate' => $item->art_innovate,
                    'dynamic_innovate' => $item->dynamic_innovate,
                    'interact_innovate' => $item->interact_innovate
                ];

                foreach ($typeMapping as $key => $value) {
                    $result[$key] = $item->$key;
                }
                $result['test_remark'] = $item->test_remark;
                $result['remark'] = $item->remark;
                return $result;
            });


        $header1 = ["申请人", "节目类型", "节目名称","原创节目名称", "状态", "上线时间", "投放时间", "节目属性", "联动属性",
            "H5属性", "交互属性", "Hidol属性", "定制属性", "合同编号", "合同金额",
            "艺术风格创新点", "动效体验创新点", "交互技术创新点"];
        foreach ($typeMapping as $item) {
            $header1[] = $item;
        }
        $header1 = array_merge($header1, ["测试备注", "节目备注"]);

        $header2 = [];
        foreach ($header1 as $header) {
            $header2[] = '';
        }

        $data = collect(array_merge([$header1, $header2], $project->toArray()));

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

                //编码被科学计数问题
                $event->sheet->getStyle('A1:A' . $this->data->count())->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:' . $this->change(count($this->header) - 1) . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change(count($this->header) - 1) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change(count($this->header) - 1) . '2')
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