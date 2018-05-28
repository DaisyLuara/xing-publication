<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/25
 * Time: 16:48
 */

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProjectByPointExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->projectId = $request->project_id;

        $this->fileName = '节目-点位数据统计表';
    }

    public function collection()
    {
        $projectName = DB::connection('ar')->table('ar_product_list')
            ->where('id', '=', $this->projectId)
            ->select('name')
            ->first();
        $data = collect();
        $header1 = ['点位', $projectName->name, '', '', '', '', '', '', ''];
        $header2 = ['', '', '', '', '', '', '', '', ''];
        $header3 = ['', '围观人数', '玩家人数', '参与率', '生成数', '热度', '扫码', '扫码率', '会员'];
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);

        $totalNum = DB::connection('ar')->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d') between '{$this->startDate}' and '{$this->endDate}' ")
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->where('apl.id', '=', $this->projectId)
            ->selectRaw("sum(looknum) as looknum,sum(playernum) as playernum,sum(outnum) as outnum,sum(scannum) as scannum,sum(lovenum) as lovenum")
            ->first();
        $total = [
            'total' => 'Total',
            'lookNum' => $totalNum->looknum,
            'playerNum' => $totalNum->playernum,
            'attendRate' => (round(($totalNum->looknum == 0) ? 0 : $totalNum->playernum / $totalNum->looknum, 2) * 100) . '%',
            'outNum' => $totalNum->outnum,
            'heat' => round(($totalNum->playernum == 0) ? 0 : $totalNum->outnum / $totalNum->playernum, 2),
            'scanNum' => $totalNum->scannum,
            'scanRate' => (round(($totalNum->outnum == 0) ? 0 : $totalNum->scannum / $totalNum->outnum, 2) * 100) . '%',
            'loveNum' => $totalNum->lovenum
        ];
        $data->push($total);

        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d') between '{$this->startDate}' and '{$this->endDate}'")
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->where('apl.id', '=', $this->projectId)
            ->groupBy('fcl.oid')
            ->selectRaw("aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum,sum(playernum) as playernum,sum(outnum) as outnum,sum(scannum) as scannum,sum(lovenum) as lovenum")
            ->get();

        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);
            $aa = [
                'pointName' => $item['areaName'] . '-' . $item['marketName'] . '-' . $item['pointName'],
                'lookNum' => $item['looknum'],
                'playerNum' => $item['playernum'],
                'attendRate' => (round(($item['looknum'] == 0) ? 0 : $item['playernum'] / $item['looknum'], 2) * 100) . '%',
                'outNum' => $item['outnum'],
                'heat' => round(($item['playernum'] == 0) ? 0 : $item['outnum'] / $item['playernum'], 2),
                'scanNum' => $item['scannum'],
                'scanRate' => (round(($item['outnum'] == 0) ? 0 : $item['scannum'] / $item['outnum'], 2) * 100) . '%',
                'loveNum' => $item['lovenum']
            ];
            $data->push($aa);
        });

        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setMergeCells(['A1:A3', 'B1:I2']);

                $event->sheet->getDelegate()
                    ->getStyle('A1:I' . $this->data->count())
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:I' . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:I3')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()->freezePane('B4');
            }
        ];
    }

}