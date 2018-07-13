<?php

namespace App\Exports;

use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PointDailyAverageExport extends AbstractExport
{

    public function __construct($request)
    {
        $this->fileName = '点位日均数据';
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->alias = $request->alias;
        $this->sceneId = $request->scene_id;
    }

    public function collection()
    {
        $data = collect();
        $header1 = ['点位', '围观数', '围观日均', '玩家总数', '玩家日均', '会员总数', '会员日均', '二维码生成数', '扫码数', '有效天数', '时间'];
        $header2 = ['', '', '', '', '', '', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);

        $query = DB::connection('ar')->table('face_count_log as fcl');
        if ($this->alias) {
            $query->where('fcl.belong', '=', $this->alias);
        } else {
            $query->where('fcl.belong', '=', 'all');
        }
        if ($this->sceneId) {
            $query->where('ao.sid', '=', $this->sceneId);
        }
        $faceCount = $query->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d') between '{$this->startDate}' and '{$this->endDate}' ")
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335])
            ->groupBy('fcl.oid')
            ->orderBy('aoa.areaid', 'desc')
            ->orderBy('aom.marketid', 'desc')
            ->orderBy('ao.oid', 'desc')
            ->selectRaw("aoa.name as areaName,aom.name as marketName,ao.name as pointName,count(*) as days, sum(looknum) as looknum,sum(playernum) as playernum,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum,concat_ws(',', date_format(min(fcl.date), '%Y-%m-%d'), date_format(max(fcl.date), '%Y-%m-%d')) as date")
            ->get();
        $total = [
            'total' => 'Total',
            'lookSum' => 0,
            'lookAverSum' => 0,
            'playerSum' => 0,
            'playerAverSum' => 0,
            'loveSum' => 0,
            'loveAverSum' => 0,
            'outSum' => 0,
            'scanSum' => 0,
            'days' => 0,
            'date' => $this->startDate . '|' . $this->endDate
        ];
        $faceCount->each(function ($item) use (&$data, &$total) {
            $aa = [
                'pointName' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'lookNum' => $item->looknum,
                'lookNumAver' => round($item->looknum / $item->days, 0),
                'playerNum' => $item->playernum,
                'playerNumAver' => round($item->playernum / $item->days, 0),
                'loveNum' => $item->lovenum,
                'loveNumAver' => round($item->lovenum / $item->days, 0),
                'outNum' => $item->outnum,
                'scanNum' => $item->scannum,
                'days' => $item->days
            ];

            $date = explode(',', $item->date);
            if ($date[0] == $date[1]) {
                $aa['date'] = $date[0];
            } else {
                $aa['date'] = $date[0] . '|' . $date[1];
            }
            $data->push($aa);
            $total = [
                'total' => '总计',
                'lookSum' => $total['lookSum'] + $aa['lookNum'],
                'lookAverSum' => $total['lookAverSum'] + $aa['lookNumAver'],
                'playerSum' => $total['playerSum'] + $aa['playerNum'],
                'playerAverSum' => $total['playerAverSum'] + $aa['playerNumAver'],
                'loveSum' => $total['loveSum'] + $aa['loveNum'],
                'loveAverSum' => $total['loveAverSum'] + $aa['loveNumAver'],
                'outSum' => $total['outSum'] + $aa['outNum'],
                'scanSum' => $total['scanSum'] + $aa['scanNum'],
                'days' => (new Carbon($this->endDate))->diffInDays(new Carbon($this->startDate)) + 1,
                'date' => $total['date']
            ];
        });
        $total['lookAverSum'] = floor($total['lookAverSum'] / $faceCount->count());
        $total['playerAverSum'] = floor($total['playerAverSum'] / $faceCount->count());
        $total['loveAverSum'] = floor($total['loveAverSum'] / $faceCount->count());
        $data->push($total);
        $this->data = $data;
        return $data;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = ['A1:A2', 'B1:B2', 'C1:C2', 'D1:D2', 'E1:E2', 'F1:F2', 'G1:G2', 'H1:H2', 'I1:I2', 'J1:J2', 'K1:K2'];

                //合并单元格
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:K' . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:K' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                //表头加粗
                $event->sheet->getDelegate()
                    ->getStyle('A1:K2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A' . $this->data->count() . ':K' . $this->data->count())
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                //冻结表头
                $event->sheet->getDelegate()->freezePane('A3');
            }
        ];
    }

}