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
            ->whereNotIn('xs_face_count_log.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupby('xs_face_count_log.belong')
            ->orderBy('ar_product_list.name')
            ->selectRaw('ar_product_list.name as name,count(xs_face_count_log.oid) as pushNum ,sum(looknum) as lookNum ,sum(playernum7) as playerNum7,sum(playernum20) as playerNum20 ,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum')
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