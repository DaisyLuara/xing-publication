<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/29
 * Time: 17:24
 */

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class MarketingTopExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->fileName = '营销创意成果点位top100';
        $this->sceneId = $request->scene_id;
    }

    public function collection()
    {
        $query = DB::connection('ar')->table('xs_face_count_log as fcl');
        if ($this->sceneId) {
            $query->whereRaw("ao.sid='$this->sceneId'");
        }

        $startClientdate = strtotime($this->startDate) * 1000;
        $endClientdate = strtotime($this->endDate) * 1000;
        $faceCount1 = $query->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff', 'ao.bd_z', '=', 'admin_staff.z')
            ->whereRaw("fcl.clientdate between '$startClientdate' and '$endClientdate' and fcl.oid not in ('16', '19', '30', '31', '177','182','327','328','329','334','335','540') and aom.marketid <> '15' and aos.name<>'EXE颜镜店' and aos.name<>'星视度研发' and admin_staff.realname<>'颜镜店'")
            ->groupBy(DB::raw("date_format(fcl.date, '%Y-%m-%d'),fcl.oid,fcl.belong"))
            ->orderBy('date')
            ->orderBy('apl.id')
            ->orderBy('looknum', 'desc')
            ->selectRaw("date_format(fcl.date, '%Y-%m-%d') as date,apl.name as name,count(*) as days,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(looknum) as looknum,sum(playernum7)as playernum7,sum(playernum15) as playernum15 ,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(phonenum) as phonenum,sum(oanum) as oanum,sum(phonetimes) as phonetimes,sum(oatimes) as oatimes,sum(coupontimes) as coupontimes,sum(verifytimes) as verifytimes");

        $faceCount2 = DB::connection('ar')->table(DB::raw("({$faceCount1->toSql()}) a,(select @gn := 0)  b"))
            ->selectRaw("  @gn := case when (@date=date and @name = name) then @gn + 1 else 1 end gn,@date:=date date,@name := name name,days,playtimes7,playtimes15,playtimes21,looknum,playernum7,playernum15,playernum21,phonenum,oanum,phonetimes,oatimes,omo_outnum,omo_scannum,coupontimes,verifytimes");

        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount2->toSql()}) c"))
            ->selectRaw("name,sum(days) as pushnum,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(looknum) as looknum,sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(phonenum) as phonenum,sum(oanum) as oanum,sum(phonetimes) as phonetimes,sum(oatimes) as oatimes,sum(coupontimes) as coupontimes,sum(verifytimes) as verifytimes")
            ->whereRaw("gn<=100")
            ->groupBy('name')
            ->get();
        $data = collect();
        $header1 = ['节目名称', '7″fCPE', '', '15″fCPE', '', '21″fCPE', '', '7″uCPE', '', '15″uCPE', '', '21″uCPE', '', 'uCPA(去重)', '', '', '', 'fCPA(不去重)', '', '', '', 'CPS', '1', '2', '5', '20', '合计'];
        $header2 = ['', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', 'omo', '领券', '公众号', '手机号', 'omo', '领券', '公众号', '手机号', '核销券', '7″uCPE', '15″uCPE', '21″uCPE', 'uCPA', ''];
        $header3 = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);
            $aa = [
                'name' => $item['name'],
                'playtimes7' => $item['playtimes7'],
                'playtimes7_average' => round($item['playtimes7'] / $item['pushnum'], 0),
                'playtimes15' => $item['playtimes15'],
                'playtimes15_average' => round($item['playtimes15'] / $item['pushnum'], 0),
                'playtimes21' => $item['playtimes21'],
                'playtimes21_average' => round($item['playtimes21'] / $item['pushnum'], 0),
                'playernum7' => $item['playernum7'] ? $item['playernum7'] : 0,
                'playernum7_average' => round(($item['playernum7'] / $item['pushnum']), 0),
                'playernum15' => $item['playernum15'] ? $item['playernum15'] : 0,
                'playernum15_average' => round(($item['playernum15'] / $item['pushnum']), 0),
                'playernum21' => $item['playernum21'] ? $item['playernum21'] : 0,
                'playernum21_average' => round(($item['playernum21'] / $item['pushnum']), 0),
                'omo_outnum' => $item['omo_outnum'],
                'coupontimes_1' => $item['coupontimes'],
                'oanum' => $item['oanum'],
                'phonenum' => $item['phonenum'],
                'omo_scannum' => $item['omo_scannum'],
                'coupontimes_2' => $item['coupontimes'],
                'oatimes' => $item['oatimes'],
                'phonetimes' => $item['phonetimes'],
                'verifytimes' => $item['verifytimes']
            ];
            $player7Money = round($aa['playernum7'] * 0.01, 2);
            $player15Money = round($aa['playernum15'] * 0.02, 2);
            $player21Money = round($aa['playernum21'] * 0.05, 2);
            $uCPAMoney = round($aa['omo_outnum'] * 0.2, 2);
            $totalMoney = $player7Money + $player15Money + $player21Money + $uCPAMoney;


            $aa['playernum7_money'] = $player7Money;
            $aa['playernum15_money'] = $player15Money;
            $aa['playernum21_money'] = $player21Money;
            $aa['uCPA_money'] = $uCPAMoney;
            $aa['total_money'] = $totalMoney;

            $data->push($aa);
        });
        $this->data = $data;
        return $data;

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = [
                    'A1:A3', 'B1:C1', 'B2:B3', 'C2:C3', 'D1:E1', 'D2:D3', 'E2:E3',
                    'F1:G1', 'F2:F3', 'G2:G3', 'H1:I1', 'H2:H3', 'I2:I3',
                    'J1:K1', 'J2:J3', 'K2:K3', 'L1:M1', 'L2:L3', 'M2:M3',
                    'N1:Q1', 'N2:N3', 'O2:O3', 'P2:P3', 'Q2:Q3', 'R1:U1',
                    'R2:R3', 'S2:S3', 'T2:T3', 'U2:U3', 'V2:V3', 'W2:W3',
                    'X2:X3', 'Y2:Y3', 'Z2:Z3', 'AA1:AA3'
                ];
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()
                    ->getStyle('A1:AA' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:AA3')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:AA' . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);
                $event->sheet->getDelegate()->freezePane('A4');
            }
        ];
    }

}