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
        $header1 = ['点位', '属性', '场景', 'BD', '7″fCPE', '', '15″fCPE', '', '21″fCPE', '', '7″uCPE', '', '15″uCPE', '', '21″uCPE', '', 'uCPA(去重)', '', '', '', 'fCPA(不去重)', '', '', '', '核销', '有效天数', '时间'];
        $header2 = ['', '', '', '', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', '总数', '平均数', 'omo', '领券数', '公众号', '手机号', 'omo', '领券数', '公众号', '手机号', '', '', ''];
        $header3 = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);

        $query = DB::connection('ar')->table('xs_face_count_log as fcl');
        if ($this->alias) {
            $query->where('fcl.belong', '=', $this->alias);
        } else {
            $query->where('fcl.belong', '=', 'all');
        }
        if ($this->sceneId) {
            $query->where('ao.sid', '=', $this->sceneId);
        }

        $startClientdate = strtotime($this->startDate) * 1000;
        $endClientdate = strtotime($this->endDate) * 1000;
        $faceCount = $query->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->leftJoin('avr_official_contract as aoc', 'ao.oid', '=', 'aoc.oid')
            ->join('admin_staff', 'ao.bd_uid', '=', 'admin_staff.uid')
            ->whereRaw("fcl.clientdate between '$startClientdate' and '$endClientdate' and ao.marketid<>15 and aos.name<>'EXE颜镜店' and aos.name<>'星视度研发' and admin_staff.realname<>'颜镜店'")
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335, 540])
            ->groupBy('fcl.oid')
            ->orderBy('aoa.areaid', 'desc')
            ->orderBy('aom.marketid', 'desc')
            ->orderBy('ao.oid', 'desc')
            ->selectRaw("aoa.name as areaName,aom.name as marketName,ao.name as pointName,aos.name as scene,aoc.type as type,admin_staff.realname as BDName,count(*) as days, sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21,sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum,sum(oanum) as oanum,sum(phonenum) as phonenum,sum(omo_scannum) as omo_scannum,sum(oatimes) as oatimes,sum(phonetimes) as phonetimes,sum(coupontimes) as coupontimes,sum(verifytimes) as verifytimes,concat_ws(',', date_format(min(fcl.date), '%Y-%m-%d'), date_format(max(fcl.date), '%Y-%m-%d')) as date")
            ->get();
        $total = [];
        $typeMapping = [
            'free' => '免费入驻',
            'pay' => '付费入驻',
            'sell' => '出售',
            'lease' => '租借',
            'activity' => '活动',
            'agent' => '代理',
            'tmp' => '过桥'
        ];
        $faceCount->each(function ($item) use (&$data, &$total, $typeMapping) {
            $aa = [
                'pointName' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'type' => $item->type ? $typeMapping[$item->type] : null,
                'scene' => $item->scene,
                'BDName' => $item->BDName,
                'playtimes7' => $item->playtimes7,
                'playtimes7Aver' => round($item->playtimes7 / $item->days, 0),
                'playtimes15' => $item->playtimes15,
                'playtimes15Aver' => round($item->playtimes15 / $item->days, 0),
                'playtimes21' => $item->playtimes21,
                'playtimes21Aver' => round($item->playtimes21 / $item->days, 0),
                'playernum7' => $item->playernum7,
                'playernum7Aver' => round($item->playernum7 / $item->days, 0),
                'playernum15' => $item->playernum15,
                'playernum15Aver' => round($item->playernum15 / $item->days, 0),
                'playernum21' => $item->playernum21,
                'playernum21Aver' => round($item->playernum21 / $item->days, 0),
                'omo_outnum' => $item->omo_outnum,
                'couponnum' => $item->coupontimes,
                'oanum' => $item->oanum,
                'phonenum' => $item->phonenum,
                'omo_scannum' => $item->omo_scannum,
                'coupontimes' => $item->coupontimes,
                'oatimes' => $item->oatimes,
                'phonetimes' => $item->phonetimes,
                'verifytimes' => $item->verifytimes,
                'days' => $item->days
            ];

            $date = explode(',', $item->date);
            if ($date[0] == $date[1]) {
                $aa['date'] = $date[0];
            } else {
                $aa['date'] = $date[0] . '|' . $date[1];
            }
            $data->push($aa);
            $total[] = $aa;
        });
        $totalData = [
            'total' => '总计',
            'type' => '',
            'BDName' => '',
            'scene' => '',
            'playtimes7' => array_sum(array_column($total, 'playtimes7')),
            'playtimes7Aver' => array_sum(array_column($total, 'days')) == 0 ? 0 : floor(array_sum(array_column($total, 'playtimes7')) / array_sum(array_column($total, 'days'))),
            'playtimes15' => array_sum(array_column($total, 'playtimes15')),
            'playtimes15Aver' => array_sum(array_column($total, 'days')) == 0 ? 0 : floor(array_sum(array_column($total, 'playtimes15')) / array_sum(array_column($total, 'days'))),
            'playtimes21' => array_sum(array_column($total, 'playtimes21')),
            'playtimes21Aver' => array_sum(array_column($total, 'days')) == 0 ? 0 : floor(array_sum(array_column($total, 'playtimes21')) / array_sum(array_column($total, 'days'))),
            'playernum7' => array_sum(array_column($total, 'playernum7')),
            'playernum7Aver' => array_sum(array_column($total, 'days')) == 0 ? 0 : floor(array_sum(array_column($total, 'playernum7')) / array_sum(array_column($total, 'days'))),
            'playernum15' => array_sum(array_column($total, 'playernum15')),
            'playernum15Aver' => array_sum(array_column($total, 'days')) == 0 ? 0 : floor(array_sum(array_column($total, 'playernum15')) / array_sum(array_column($total, 'days'))),
            'playernum21' => array_sum(array_column($total, 'playernum21')),
            'playernum21Aver' => array_sum(array_column($total, 'days')) == 0 ? 0 : floor(array_sum(array_column($total, 'playernum21')) / array_sum(array_column($total, 'days'))),
            'omo_outnum' => array_sum(array_column($total, 'omo_outnum')),
            'couponnum' => array_sum(array_column($total, 'couponnum')),
            'oanum' => array_sum(array_column($total, 'oanum')),
            'phonenum' => array_sum(array_column($total, 'phonenum')),
            'omo_scannum' => array_sum(array_column($total, 'omo_scannum')),
            'coupontimes' => array_sum(array_column($total, 'coupontimes')),
            'oatimes' => array_sum(array_column($total, 'oatimes')),
            'phonetimes' => array_sum(array_column($total, 'phonetimes')),
            'verifytimes' => array_sum(array_column($total, 'verifytimes')),
            'days' => array_sum(array_column($total, 'days')),
            'date' => $this->startDate . '|' . $this->endDate
        ];
        $data->push($totalData);
        $this->data = $data;
        return $data;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = [
                    'A1:A3', 'B1:B3', 'C1:C3', 'D1:D3', 'E1:F1', 'E2:E3', 'F2:F3',
                    'G1:H1', 'G2:G3', 'H2:H3', 'I1:J1', 'I2:I3', 'J2:J3',
                    'K1:L1', 'K2:K3', 'L2:L3', 'M1:N1', 'M2:M3', 'N2:N3',
                    'O1:P1', 'O2:O3', 'P2:P3', 'Q1:T1', 'Q2:Q3', 'R2:R3',
                    'S2:S3', 'T2:T3', 'U1:X1', 'U2:U3', 'V2:V3', 'W2:W3',
                    'X2:X3', 'Y1:Y3', 'Z1:Z3', 'AA1:AA3'
                ];

                //合并单元格
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:AA' . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:AA' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                //表头加粗
                $event->sheet->getDelegate()
                    ->getStyle('A1:AA3')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A' . $this->data->count() . ':AA' . $this->data->count())
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                //冻结表头
                $event->sheet->getDelegate()->freezePane('A4');
            }
        ];
    }

}