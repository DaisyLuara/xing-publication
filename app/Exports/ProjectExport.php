<?php

namespace App\Exports;

use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProjectExport implements FromCollection, WithStrictNullComparison, WithEvents
{
    public function collection()
    {
        $date1 = date_format(Carbon::now()->addMonth(-5), 'Y-m');
        $date2 = date_format(Carbon::now()->addMonth(-4), 'Y-m');
        $date3 = date_format(Carbon::now()->addMonth(-3), 'Y-m');
        $date4 = date_format(Carbon::now()->addMonth(-2), 'Y-m');
        $date5 = date_format(Carbon::now()->addMonth(-1), 'Y-m');
        $date6 = date_format(Carbon::now(), 'Y-m');

        $faceCount = DB::connection('ar')->table('face_count_log')
            ->join('ar_product_list', 'belong', '=', 'versionname')
            ->join('avr_official', 'face_count_log.oid', '=', 'avr_official.oid')
            ->join('avr_official_area', 'avr_official.areaid', '=', 'avr_official_area.areaid')
            ->join('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
            ->whereRaw("date_format(face_count_log.date, '%Y-%m') BETWEEN '$date1' AND '$date6' and avr_official.oid not in ('16', '19', '30', '31', '335', '334', '329', '328', '327')")
            ->groupby(DB::raw("belong,date_format(face_count_log.date,'%Y-%m')"))
            ->selectRaw("ar_product_list.name as projectName,date_format(face_count_log.date,'%Y-%m') as date, sum(looknum) as lookNum ,sum(playernum) as playerNum ,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum");

        $sql1 = "a.projectName ,max(case a.date when '$date1' then concat_ws(',',cast(a.looknum as char),cast(a.playernum as char),cast(a.lovenum as char),cast(a.outnum as char),cast(a.scannum as char)) else 0 end) '$date1'";
        $sql2 = ",max(case a.date when '$date2' then concat_ws(',',cast(a.lookNum as char),cast(a.playerNum as char),cast(a.loveNum as char),cast(a.outNum as char),cast(a.scanNum as char)) else 0 end) '$date2'";
        $sql3 = ",max(case a.date when '$date3' then concat_ws(',',cast(a.lookNum as char),cast(a.playerNum as char),cast(a.loveNum as char),cast(a.outNum as char),cast(a.scanNum as char)) else 0 end) '$date3'";
        $sql4 = ",max(case a.date when '$date4' then concat_ws(',',cast(a.lookNum as char),cast(a.playerNum as char),cast(a.loveNum as char),cast(a.outNum as char),cast(a.scanNum as char)) else 0 end) '$date4'";
        $sql5 = ",max(case a.date when '$date5' then concat_ws(',',cast(a.lookNum as char),cast(a.playerNum as char),cast(a.loveNum as char),cast(a.outNum as char),cast(a.scanNum as char)) else 0 end) '$date5'";
        $sql6 = ",max(case a.date when '$date6' then concat_ws(',',cast(a.lookNum as char),cast(a.playerNum as char),cast(a.loveNum as char),cast(a.outNum as char),cast(a.scanNum as char)) else 0 end) '$date6'";
        $sql = $sql1 . $sql2 . $sql3 . $sql4 . $sql5 . $sql6;
        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount->toSql()}) as a"))
            ->selectRaw("$sql")
            ->groupBy(DB::raw('a.projectName'))
            ->get();

        $data = collect();
        $header1 = ['节目', $date1, '', '', '', '', $date2, '', '', '', '', $date3, '', '', '', '', $date4, '', '', '', '', $date5, '', '', '', '', $date6, '', '', '', ''];
        $header2 = [''];
        for ($i = 0; $i < 6; $i++) {
            $header2 = array_merge($header2, ['', '', '', '', '']);
        }
        $header3 = [''];
        for ($i = 0; $i < 6; $i++) {
            $header3 = array_merge($header3, ['围观数', '玩家数', '会员数', '生成数', '扫码数']);
        }
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);

            $aa = [];
            foreach ($item as $key => $value) {

                if ($key == 'projectName') {
                    $aa['projectName'] = $value;
                } else {
                    if ($value == 0) {
                        $aa[$key . '-' . 'lookNum'] = 0;
                        $aa[$key . '-' . 'playerNum'] = 0;
                        $aa[$key . '-' . 'loveNum'] = 0;
                        $aa[$key . '-' . 'outNum'] = 0;
                        $aa[$key . '-' . 'scanNum'] = 0;
                    } else {
                        $num = explode(',', $value);
                        $aa[$key . '-' . 'lookNum'] = $num['0'];
                        $aa[$key . '-' . 'playerNum'] = $num['1'];
                        $aa[$key . '-' . 'loveNum'] = $num['2'];
                        $aa[$key . '-' . 'outNum'] = $num['3'];
                        $aa[$key . '-' . 'scanNum'] = $num['4'];
                    }
                }
            }
            $data->push($aa);
        });
        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:AE' . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '00000000']
                        ]
                    ]
                ]);
                $event->sheet->getDelegate()
                    ->getStyle('A1:AE' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->setMergeCells(['A1:A3', 'B1:F2', 'G1:K2', 'L1:P2', 'Q1:U2', 'V1:Z2', 'AA1:AE2']);
            }
        ];
    }
}