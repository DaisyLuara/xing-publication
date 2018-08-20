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
    }

    public function collection()
    {
        $faceCount1 = DB::connection('ar')->table('xs_face_count_log as fcl')
            ->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw("date_format(fcl.date, '%Y-%m-%d') BETWEEN '{$this->startDate}' AND '{$this->endDate}' and fcl.oid not in ('16', '19', '30', '31', '177','182','327','328','329','334','335','540') and aom.marketid <> '15'")
            ->groupBy(DB::raw("fcl.oid,fcl.belong"))
            ->orderBy('apl.id')
            ->orderBy('looknum', 'desc')
            ->selectRaw("apl.name as name,count(*) as days,sum(looknum) as looknum ,sum(playernum7)as playernum7,sum(playernum20) as playernum20 ,sum(playernum) as playernum,sum(outnum) as outnum,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(phonenum) as phonenum");

        $faceCount2 = DB::connection('ar')->table(DB::raw("({$faceCount1->toSql()}) a,(select @gn := 0)  b"))
            ->selectRaw("  @gn := case when @name = name then @gn + 1 else 1 end gn,@name := name name,days,looknum,playernum7,playernum20,playernum,phonenum,outnum,omo_outnum,omo_scannum");

        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount2->toSql()}) c"))
            ->selectRaw("name,sum(days) as pushNum,sum(looknum) as lookNum,sum(playernum7) as playerNum7,sum(playernum20) as playerNum20,sum(playernum) as playerNum,sum(outnum) as outNum,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(phonenum) as phoneNum")
            ->whereRaw("gn<=100")
            ->groupBy('name')
            ->get();
        $data = collect();
        $header1 = ['节目名称', 'CPF', '', 'oCPF', '', 'CPR', '', '铁杆玩家', '', '生成数', 'CPA', '扫码率', 'CPL', '', '1', '2', '5', '4', '10', '20', '合计'];
        $header2 = ['', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '', '', '', '总数', '平均数', 'CPF', 'oCPF', 'CPR', '生成数', 'CPA', 'CPL', ''];
        $header3 = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);
            $aa = [
                'name' => $item['name'],
                'lookNum' => $item['lookNum'],
                'look_average' => round($item['lookNum'] / $item['pushNum'], 0),
                'player7' => $item['playerNum7'] ? $item['playerNum7'] : 0,
                'player7_average' => round(($item['playerNum7'] / $item['pushNum']), 0),
                'player20' => $item['playerNum20'] ? $item['playerNum20'] : 0,
                'player20_average' => round(($item['playerNum20'] / $item['pushNum']), 0),
                'playerNum' => $item['playerNum'],
                'player_average' => round(($item['playerNum'] / $item['pushNum']), 0),
                'outNum' => $item['outNum'],
                'omo' => $item['omo_outnum'] . '|' . $item['omo_scannum'],
                'rate' => (round(($item['outNum'] == 0) ? 0 : $item['omo_outnum'] / $item['outNum'], 2) * 100) . '%',
                'phoneNum' => $item['phoneNum'],
                'love_average' => round(($item['phoneNum'] / $item['pushNum']), 0),
            ];
            $faceMoney = round($aa['lookNum'] * 0.01, 0);
            $player7Money = round($aa['player7'] * 0.02, 0);
            $player20Money = round($aa['player20'] * 0.05, 0);
            $outMoney = round($aa['outNum'] * 0.04, 0);
            $scanMoney = round($item['omo_outnum'] * 0.1, 0);
            $loveMoney = round($aa['phoneNum'] * 0.2, 0);
            $totalMoney = $faceMoney + $player7Money + $player20Money + $outMoney + $scanMoney + $loveMoney;


            $aa['face_money'] = $faceMoney;
            $aa['playerNum7_money'] = $player7Money;
            $aa['playerNum20_money'] = $player20Money;
            $aa['out_money'] = $outMoney;
            $aa['scan_money'] = $scanMoney;
            $aa['love_money'] = $loveMoney;
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
                    'A1:A3', 'B1:C1', 'D1:E1', 'F1:G1', 'H1:I1', 'M1:N1',
                    'B2:B3', 'C2:C3', 'D2:D3', 'E2:E3', 'F2:F3',
                    'G2:G3', 'H2:H3', 'I2:I3', 'J1:J3', 'K1:K3',
                    'L1:L3', 'M2:M3', 'N2:N3', 'O2:O3', 'P2:P3',
                    'Q2:Q3', 'R2:R3', 'S2:S3', 'T2:T3', 'U1:U3'
                ];
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()
                    ->getStyle('A1:U' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:U3')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:U' . $this->data->count())
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