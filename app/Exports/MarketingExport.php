<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MarketingExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->fileName = '营销创意成果';
    }

    public function collection()
    {
        $faceCount = DB::connection('ar')->table('xs_face_count_log')
            ->join('ar_product_list', 'belong', '=', 'versionname')
            ->join('avr_official', 'xs_face_count_log.oid', '=', 'avr_official.oid')
            ->join('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
            ->where('avr_official_market.marketid', '<>', 15)
            ->whereRaw("date_format(xs_face_count_log.date, '%Y-%m-%d') BETWEEN '{$this->startDate}' AND '{$this->endDate}'")
            ->whereNotIn('xs_face_count_log.oid', [16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335])
            ->groupby('xs_face_count_log.belong')
            ->orderBy('ar_product_list.name')
            ->selectRaw('ar_product_list.name as name,count(xs_face_count_log.oid) as pushNum ,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(looknum) as lookNum ,sum(playernum7) as playerNum7,sum(playernum20) as playerNum20 ,sum(playernum) as playerNum,sum(outnum) as outNum,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(phonenum) as phoneNum')
            ->get();
        $data = collect();
        $header1 = ['节目名称', '7″CPF', '', '15″CPF', '', '21″CPF', '', 'CPF', '', 'oCPF', '', 'CPR', '', '铁杆玩家', '', '生成数', 'CPA', '扫码率', 'CPL', '', '1', '2', '5', '4', '10', '20', '合计'];
        $header2 = ['', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '', '', '', '总数', '平均数', 'CPF', 'oCPF', 'CPR', '生成数', 'CPA', 'CPL', ''];
        $header3 = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);
            $aa = [
                'name' => $item['name'],
                'playtimes7' => $item['playtimes7'],
                'playtimes7_average' => round($item['playtimes7'] / $item['pushNum'], 0),
                'playtimes15' => $item['playtimes15'],
                'playtimes15_average' => round($item['playtimes15'] / $item['pushNum'], 0),
                'playtimes21' => $item['playtimes21'],
                'playtimes21_average' => round($item['playtimes21'] / $item['pushNum'], 0),
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
            $scanMoney = round($item['omo_scannum'] * 0.1, 0);
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
                    'A1:A3', 'B1:C1', 'B2:B3', 'C2:C3', 'D1:E1', 'D2:D3', 'E2:E3',
                    'F1:G1', 'F2:F3', 'G2:G3', 'H1:I1', 'H2:H3', 'I2:I3',
                    'J1:K1', 'J2:J3', 'K2:K3', 'L1:M1', 'L2:L3', 'M2:M3',
                    'N1:O1', 'N2:N3', 'O2:O3', 'P1:P3', 'Q2:Q3', 'R2:R3',
                    'S1:T1', 'S2:S3', 'T2:T3', 'U2:U3', 'V2:V3', 'W2:W3',
                    'X2:X3', 'Y2:Y3', 'Z2:Z3', 'AA1:AA3'
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