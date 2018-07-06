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
        $faceCount1 = DB::connection('ar')->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('face_people_time_active_player', function ($join) {
                $join->on('fcl.oid', '=', 'face_people_time_active_player.oid')
                    ->on('fcl.belong', '=', 'face_people_time_active_player.belong')
                    ->whereRaw("date_format(fcl.date,'%Y-%m-%d')=date_format(face_people_time_active_player.date,'%Y-%m-%d')");
            }, null, null, 'left')
            ->whereRaw("date_format(fcl.date, '%Y-%m-%d') BETWEEN '{$this->startDate}' AND '{$this->endDate}' and fcl.oid not in ('16', '19', '30', '31', '335', '334', '329', '328', '327') and aom.marketid <> '15'")
            ->groupBy(DB::raw("fcl.oid,fcl.belong"))
            ->orderBy('apl.id')
            ->orderBy('looknum', 'desc')
            ->selectRaw("apl.name as name,count(*) as days,sum(looknum) as looknum ,sum(playernum7)as playernum7,sum(playernum20) as playernum20 ,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum");

        $faceCount2 = DB::connection('ar')->table(DB::raw("({$faceCount1->toSql()}) a,(select @gn := 0)  b"))
            ->selectRaw("  @gn := case when @name = name then @gn + 1 else 1 end gn,@name := name name,days,looknum,playernum7,playernum20,lovenum,outnum,scannum");

        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount2->toSql()}) c"))
            ->selectRaw("name,sum(days) as pushNum,sum(looknum) as lookNum,sum(playernum7) as playerNum7,sum(playernum20) as playerNum20,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum")
            ->whereRaw("gn<=100")
            ->groupBy('name')
            ->get();
        $data = collect();
        $header1 = ['节目名称', 'CPF', '', 'oCPF', '', 'CPR', '', '生成数', 'CPA', '扫码率', 'CPL', '', '1', '2', '5', '4', '10', '20', '合计'];
        $header2 = ['', '总数', '平均数', '总数', '平均数', '总数', '平均数', '', '', '', '总数', '平均数', 'CPF', 'oCPF', 'CPR', '生成数', 'CPA', 'CPL', ''];
        $header3 = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);
            $item = [
                'name' => $item['name'],
                'lookNum' => $item['lookNum'],
                'look_average' => round($item['lookNum'] / $item['pushNum'], 0),
                'player7' => $item['playerNum7'] ? $item['playerNum7'] : 0,
                'player7_average' => round(($item['playerNum7'] / $item['pushNum']), 0),
                'player20' => $item['playerNum20'] ? $item['playerNum20'] : 0,
                'player20_average' => round(($item['playerNum20'] / $item['pushNum']), 0),
                'outNum' => $item['outNum'],
                'scanNum' => $item['scanNum'],
                'rate' => (round(($item['outNum'] == 0) ? 0 : $item['scanNum'] / $item['outNum'], 2) * 100) . '%',
                'loveNum' => $item['loveNum'],
                'love_average' => round(($item['loveNum'] / $item['pushNum']), 0),
            ];
            $faceMoney = round($item['lookNum'] * 0.01, 0);
            $player7Money = round($item['player7'] * 0.02, 0);
            $player20Money = round($item['player20'] * 0.05, 0);
            $outMoney = round($item['outNum'] * 0.04, 0);
            $scanMoney = round($item['scanNum'] * 0.1, 0);
            $loveMoney = round($item['loveNum'] * 0.2, 0);
            $totalMoney = $faceMoney + $player7Money + $player20Money + $outMoney + $scanMoney + $loveMoney;


            $item['face_money'] = $faceMoney;
            $item['playerNum7_money'] = $player7Money;
            $item['playerNum20_money'] = $player20Money;
            $item['out_money'] = $outMoney;
            $item['scan_money'] = $scanMoney;
            $item['love_money'] = $loveMoney;
            $item['total_money'] = $totalMoney;

            $data->push($item);
        });
        $this->data = $data;
        return $data;

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = [
                    'A1:A3', 'B1:C1', 'D1:E1', 'F1:G1', 'K1:L1',
                    'B2:B3', 'C2:C3', 'D2:D3', 'E2:E3', 'F2:F3',
                    'G2:G3', 'H1:H3', 'I1:I3', 'J1:J3', 'K2:K3',
                    'L2:L3', 'M2:M3', 'N2:N3', 'O2:O3', 'P2:P3',
                    'Q2:Q3', 'R2:R3', 'S1:S3'
                ];
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()
                    ->getStyle('A1:S' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:S3')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:S' . $this->data->count())
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