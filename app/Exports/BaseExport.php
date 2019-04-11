<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseExport implements FromCollection, WithStrictNullComparison, WithEvents
{
    public $header_num;
    public $data;
    public $merge = [];

    public $merge_start = 0;
    public $merge_end = 0;

    public $fileName;

    public function collection()
    {

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $cellArray = [];
                for ($i = 1; $i <= $this->header_num; $i++) {
                    $cellArray[] = Coordinate::stringFromColumnIndex($i) . '1:' . Coordinate::stringFromColumnIndex($i) . '2';
                }

                if ($this->merge_start > 0 && $this->merge_end > 0 && $this->merge) {

                    $cell_cursor = 3;

                    foreach ($this->merge as $cellNum) {
                        if ($cellNum > 1) {
                            for ($i = $this->merge_start; $i <= $this->merge_end; $i++) {
                                $cellArray[] = Coordinate::stringFromColumnIndex($i) . $cell_cursor . ':' . Coordinate::stringFromColumnIndex($i) . ($cell_cursor + $cellNum - 1);
                            }
                        }
                        $cell_cursor += $cellNum;
                    }

                }

                $event->sheet->getDelegate()->setMergeCells($cellArray);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:' . Coordinate::stringFromColumnIndex($this->header_num) . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:' . Coordinate::stringFromColumnIndex($this->header_num) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . Coordinate::stringFromColumnIndex($this->header_num) . '2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()->freezePane('A3');
            }];

    }
}