<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseExport implements FromCollection, WithStrictNullComparison, WithEvents
{
    public $header_num;
    public $data;
    public $fileName;

    public function collection()
    {

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $cellArray = [];
                for ($i = 0; $i < $this->header_num; $i++) {
                    $cellArray[] = excelChange($i) . '1:' . excelChange($i) . '2';
                }
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:' . excelChange($this->header_num - 1) . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:' . excelChange($this->header_num - 1) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . excelChange($this->header_num - 1) . '2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()->freezePane('A3');
            }
        ];
    }
}