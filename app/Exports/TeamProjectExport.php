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
use Carbon\Carbon;
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
    protected $header_first_count;

    public function __construct($request)
    {
        $this->start_date_begin = $request->start_date_begin;
        $this->end_date_begin = $request->end_date_begin;

        $this->start_date_online = $request->start_date_online;
        $this->end_date_online = $request->end_date_online;

        $this->start_date_launch = $request->start_date_launch;
        $this->end_date_launch = $request->end_date_launch;

        $this->start_date_face = $request->start_date_face ?? Carbon::now('PRC')->subDays(8)->toDateString();
        $this->end_date_face = $request->end_date_face ?? Carbon::now('PRC')->subDays(1)->toDateString();

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
        $individualAttributeMapping = TeamProject::$individualAttributeMapping;

        //market_top
        $ar_query = DB::connection('ar')->table('xs_face_count_log as fcl');
        $faceCount1 = $ar_query->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff', 'ao.bd_uid', '=', 'admin_staff.uid')
            ->whereRaw("date_format(fcl.date, '%Y-%m-%d') BETWEEN '{$this->start_date_face}' AND '{$this->end_date_face}' and fcl.oid not in ('16', '19', '30', '31', '177','182','327','328','329','334','335','540') and aom.marketid <> '15' and aos.name<>'EXE颜镜店' and aos.name<>'星视度研发' and admin_staff.realname<>'颜镜店'")
            ->groupBy(DB::raw("date_format(fcl.date, '%Y-%m-%d'),fcl.oid,fcl.belong"))
            ->orderBy('date')
            ->orderBy('apl.id')
            ->orderBy('looknum', 'desc')
            ->selectRaw("date_format(fcl.date, '%Y-%m-%d') as date,apl.name as name,apl.versionname,count(*) as days,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(looknum) as looknum,sum(playernum7)as playernum7,sum(playernum15) as playernum15 ,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(phonenum) as phonenum,sum(oanum) as oanum,sum(phonetimes) as phonetimes,sum(oatimes) as oatimes,sum(coupontimes) as coupontimes,sum(verifytimes) as verifytimes");

        $faceCount2 = DB::connection('ar')->table(DB::raw("({$faceCount1->toSql()}) a,(select @gn := 0)  b"))
            ->selectRaw("  @gn := case when (@date=date and @name = name) then @gn + 1 else 1 end gn,@date:=date date,@name := name name,versionname,days,playtimes7,playtimes15,playtimes21,looknum,playernum7,playernum15,playernum21,phonenum,oanum,phonetimes,oatimes,omo_outnum,omo_scannum,coupontimes,verifytimes");

        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount2->toSql()}) c"))
            ->selectRaw("name,versionname,sum(days) as pushnum,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(looknum) as looknum,sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(phonenum) as phonenum,sum(oanum) as oanum,sum(phonetimes) as phonetimes,sum(oatimes) as oatimes,sum(coupontimes) as coupontimes,sum(verifytimes) as verifytimes")
            ->whereRaw("gn<=100")
            ->groupBy('name', 'versionname')
            ->get();

        $faceCountData = $faceCount->keyBy('versionname')->toArray();
        //节目
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
            tp.project_name,tp.belong,tp.status,tp.online_date,tp.launch_date,
            tp.copyright_attribute,IFNULL(tp2.project_name,'无') as copyright_project,
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
            project_type,project_name,belong,status,online_date,launch_date,
            copyright_attribute,copyright_project,
            project_attribute,link_attribute,h5_attribute,
            interaction_attribute,hidol_attribute,
            individual_attribute,contract_number,amount,
            art_innovate,dynamic_innovate,interact_innovate,remark,test_remark" . $case)
            ->groupBy("project_name", "belong")
            ->get()->map(function ($item) use ($typeMapping, $statusMapping, $projectAttributeMapping, $interactionAttributeMapping, $individualAttributeMapping, $faceCountData) {
                $interaction_attribute_text = Collect(explode(',', $item->interaction_attribute))
                    ->map(function ($v) use ($interactionAttributeMapping) {
                        return $interactionAttributeMapping[$v] ?? "--";
                    })->toArray();
                $result = [
                    'applicant' => $item->applicant,
                    'project_type' => $item->project_type == 1 ? '提前' : '正常',
                    'project_name' => $item->project_name,
                    'status' => $statusMapping[$item->status],
                    'online_date' => $item->online_date,
                    'launch_date' => $item->launch_date,
                    'copyright_attribute' => $item->copyright_attribute == 1 ? '否' : '是',
                    'copyright_project' => $item->copyright_project,
                    'project_attribute' => $projectAttributeMapping[$item->project_attribute] ?? $item->project_attribute,
                    'link_attribute' => $item->link_attribute == 1 ? '是' : '否',
                    'h5_attribute' => $item->h5_attribute == 1 ? '基础' : '复杂',
                    'interaction_attribute' => implode(',', $interaction_attribute_text),
                    'hidol_attribute' => $item->hidol_attribute == 1 ? '是' : '否',
                    'individual_attribute' => $individualAttributeMapping[$item->individual_attribute] ?? $item->individual_attribute,
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

                //face数据
                if (isset($faceCountData[$item->belong])) {
                    $face_item = $faceCountData[$item->belong];
                    $result = array_merge($result,
                        [
                            'playtimes7' => $face_item->playtimes7,
                            'playtimes7_average' => round($face_item->playtimes7 / $face_item->pushnum, 0),
                            'playtimes15' => $face_item->playtimes15,
                            'playtimes15_average' => round($face_item->playtimes15 / $face_item->pushnum, 0),
                            'playtimes21' => $face_item->playtimes21,
                            'playtimes21_average' => round($face_item->playtimes21 / $face_item->pushnum, 0),
                            'playernum7' => $face_item->playernum7 ? $face_item->playernum7 : 0,
                            'playernum7_average' => round(($face_item->playernum7 / $face_item->pushnum), 0),
                            'playernum15' => $face_item->playernum15 ? $face_item->playernum15 : 0,
                            'playernum15_average' => round(($face_item->playernum15 / $face_item->pushnum), 0),
                            'playernum21' => $face_item->playernum21 ? $face_item->playernum21 : 0,
                            'playernum21_average' => round(($face_item->playernum21 / $face_item->pushnum), 0),
                            'omo_outnum' => $face_item->omo_outnum,
                            'coupontimes_1' => $face_item->coupontimes,
                            'oanum' => $face_item->oanum,
                            'phonenum' => $face_item->phonenum,
                            'omo_scannum' => $face_item->omo_scannum,
                            'coupontimes_2' => $face_item->coupontimes,
                            'oatimes' => $face_item->oatimes,
                            'phonetimes' => $face_item->phonetimes,
                            'verifytimes' => $face_item->verifytimes,
                        ]);
                    $player7Money = round($result['playernum7'] * 0.01, 2);
                    $player15Money = round($result['playernum15'] * 0.02, 2);
                    $player21Money = round($result['playernum21'] * 0.05, 2);
                    $uCPAMoney = round($result['omo_outnum'] * 0.2, 2);
                    $totalMoney = $player7Money + $player15Money + $player21Money + $uCPAMoney;
                    $result['playernum7_money'] = $player7Money;
                    $result['playernum15_money'] = $player15Money;
                    $result['playernum21_money'] = $player21Money;
                    $result['uCPA_money'] = $uCPAMoney;
                    $result['total_money'] = $totalMoney;
                } else {
                    $result = array_merge($result,
                        [
                            'playtimes7' => null,
                            'playtimes7_average' => null,
                            'playtimes15' => null,
                            'playtimes15_average' => null,
                            'playtimes21' => null,
                            'playtimes21_average' => null,
                            'playernum7' => null,
                            'playernum7_average' => null,
                            'playernum15' => null,
                            'playernum15_average' => null,
                            'playernum21' => null,
                            'playernum21_average' => null,
                            'omo_outnum' => null,
                            'coupontimes_1' => null,
                            'oanum' => null,
                            'phonenum' => null,
                            'omo_scannum' => null,
                            'coupontimes_2' => null,
                            'oatimes' => null,
                            'phonetimes' => null,
                            'verifytimes' => null,
                            'playernum7_money' => null,
                            'playernum15_money' => null,
                            'playernum21_money' => null,
                            'uCPA_money' => null,
                            'total_money' => null,
                        ]);
                }

                return $result;
            });


        $header1 = ["申请人", "节目类型", "节目名称", "状态", "上线时间", "投放时间", "原创属性", "原创节目名称", "节目属性", "联动属性",
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

        $this->header_first_count = count($header1);
        $header1 = array_merge($header1, ['7″fCPE', '', '15″fCPE', '', '21″fCPE', '', '7″uCPE', '', '15″uCPE', '', '21″uCPE', '', 'uCPA(去重)', '', '', '', 'fCPA(不去重)', '', '', '', 'CPS', '1', '2', '5', '20', '合计']);
        $header2 = array_merge($header2, ['总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', 'omo', '领券', '公众号', '手机号', 'omo', '领券', '公众号', '手机号', '核销券', '7″uCPE', '15″uCPE', '21″uCPE', 'uCPA', '']);

        $data = collect(array_merge([$header1, $header2], $project->toArray()));

        $this->header = $header1;
        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                for ($i = 0; $i < $this->header_first_count; $i++) {
                    $cellArray[] = excelChange($i) . '1:' . excelChange($i) . '2';
                }
                $cellArray[] = excelChange($this->header_first_count) . '1:' . excelChange($this->header_first_count + 1) . '1';
                $cellArray[] = excelChange($this->header_first_count + 2) . '1:' . excelChange($this->header_first_count + 3) . '1';
                $cellArray[] = excelChange($this->header_first_count + 4) . '1:' . excelChange($this->header_first_count + 5) . '1';
                $cellArray[] = excelChange($this->header_first_count + 6) . '1:' . excelChange($this->header_first_count + 7) . '1';
                $cellArray[] = excelChange($this->header_first_count + 8) . '1:' . excelChange($this->header_first_count + 9) . '1';
                $cellArray[] = excelChange($this->header_first_count + 10) . '1:' . excelChange($this->header_first_count + 11) . '1';
                $cellArray[] = excelChange($this->header_first_count + 12) . '1:' . excelChange($this->header_first_count + 15) . '1';
                $cellArray[] = excelChange($this->header_first_count + 16) . '1:' . excelChange($this->header_first_count + 19) . '1';
                $cellArray[] = excelChange($this->header_first_count + 16) . '1:' . excelChange($this->header_first_count + 19) . '1';
                $cellArray[] = excelChange(count($this->header) - 1) . '1:' . excelChange(count($this->header) - 1) . '2';

                $event->sheet->getDelegate()->setMergeCells($cellArray);


                //编码被科学计数问题
                $event->sheet->getStyle('A1:' . excelChange(count($this->header) - 1) . $this->data->count())->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:' . excelChange(count($this->header) - 1) . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()->getStyle('A1:' . excelChange(count($this->header) - 1) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . excelChange(count($this->header) - 1) . '2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()->freezePane('A3');
            }
        ];
    }
}