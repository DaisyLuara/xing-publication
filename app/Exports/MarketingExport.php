<?php

namespace App\Exports;

use App\Models\FaceCount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class MarketingExport implements FromCollection, WithStrictNullComparison, WithHeadings, WithEvents
{
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function headings(): array
    {
        return [
            '节目名称',
            '围观数',
            '',
            '活跃用户',
            '玩家数',
            '',
            '会员数',
            '',
            '生成数',
            '扫码数',
            '扫码率',
            '1',
            '2',
            '5',
            '7',
            '10',
            '20',
            '合计',
        ];
    }

    public function collection()
    {

        $faceCount = new FaceCount();
        $query = $faceCount->query();

        $faceCount = $query->join('ar_product_list', 'belong', '=', 'versionname')
            ->whereRaw("date_format(face_count_log.date, '%Y-%m-%d') BETWEEN '{$this->startDate}' AND '{$this->endDate}'")
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupby('belong')
            ->selectRaw('ar_product_list.name as name,count(oid) as pushNum ,sum(looknum) as lookNum ,sum(playernum) as playerNum ,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum')
            ->get();
        $data = collect();
        $data->push([
            '',
            '总数',
            '平均数',
            '总数',
            '总数',
            '平均数',
            '总数',
            '平均数',
            '',
            '',
            '',
            '刷脸',
            '活跃用户',
            '大玩家',
            '生成数',
            '扫码',
            '会员',
            '',
        ]);
        $faceCount->each(function ($item) use (&$data) {
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

        return $data;
        dd($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setMergeCells(['A1:A2', 'B1:C1', 'E1:F1', 'G1:H1','I1:I2', 'J1:J2', 'K1:K2', 'R1:R2']);
            }
        ];
    }
}