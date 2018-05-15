<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MarketingExport implements FromCollection, WithStrictNullComparison, WithEvents
{
    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
    }

    public function collection()
    {
        $faceCount = DB::connection('ar')->table('face_count_log')
            ->join('ar_product_list', 'belong', '=', 'versionname')
            ->whereRaw("date_format(face_count_log.date, '%Y-%m-%d') BETWEEN '{$this->startDate}' AND '{$this->endDate}'")
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupby('belong')
            ->selectRaw('ar_product_list.name as name,count(oid) as pushNum ,sum(looknum) as lookNum ,sum(playernum) as playerNum ,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum')
            ->get();
        $data = collect();
        $header1 = ['节目名称', '围观数', '', '活跃用户', '玩家数', '', '会员数', '', '生成数', '扫码数', '扫码率', '1', '2', '5', '7', '10', '20', '合计'];
        $header2 = ['', '总数', '平均数', '总数', '总数', '平均数', '总数', '平均数', '', '', '', '刷脸', '活跃用户', '大玩家', '生成数', '扫码', '会员', ''];
        $header3 = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);
            $item = [
                'name' => $item['name'],
                'lookNum' => $item['lookNum'],
                'look_average' => round($item['lookNum'] / $item['pushNum'], 0),
                'activeNum' => ($item['playerNum'] * 2 > $item['lookNum']) ? $item['lookNum'] : $item['playerNum'] * 2,
                'playerNum' => $item['playerNum'],
                'player_average' => round(($item['playerNum'] / $item['pushNum']), 0),
                'loveNum' => $item['loveNum'],
                'love_average' => round(($item['loveNum'] / $item['pushNum']), 0),
                'outNum' => $item['outNum'],
                'scanNum' => $item['scanNum'],
                'rate' => (round(($item['outNum'] == 0) ? 0 : $item['scanNum'] / $item['outNum'], 2) * 100) . '%',

            ];
            $faceMoney = round($item['lookNum'] * 0.01, 0);
            $activeMoney = round($item['activeNum'] * 0.02, 0);
            $playerMoney = round($item['playerNum'] * 0.05, 0);
            $outMoney = round($item['outNum'] * 0.07, 0);
            $scanMoney = round($item['scanNum'] * 0.1, 0);
            $loveMoney = round($item['loveNum'] * 0.2, 0);
            $totalMoney = $faceMoney + $activeMoney + $playerMoney + $outMoney + $scanMoney + $loveMoney;


            $item['face_money'] = $faceMoney;
            $item['active_money'] = $activeMoney;
            $item['player_money'] = $playerMoney;
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
                $cellArray = ['A1:A3', 'B1:C1', 'B2:B3', 'C2:C3', 'D2:D3', 'E1:F1', 'E2:E3', 'F2:F3', 'G1:H1', 'G2:G3', 'H2:H3', 'I1:I3', 'J1:J3', 'K1:K3', 'L2:L3', 'M2:M3', 'N2:N3', 'O2:O3', 'P2:P3', 'Q2:Q3', 'R1:R3'];
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()
                    ->getStyle('A1:R' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:R2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:R' . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '00000000']
                            ]
                        ]
                    ]);
            }
        ];
    }
}