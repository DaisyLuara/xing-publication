<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\FaceCount;
use Illuminate\Http\Request;

class PointDailyAverageExport extends AbstractExport
{

    /**@var Request* */
    protected $request;

    public function __construct($request)
    {
        $this->fileName = '点位数据';
        $this->request = $request;
    }

    public function collection()
    {
        $data = null;
        $query = FaceCount::query();
        $table = $query->getModel()->getTable();
        handPointQuery($this->request, $query, 0);
        $alias = $this->request->alias ? $this->request->alias : 'all';

        $data = $query->selectRaw("date_format($table.date,'%Y-%m-%d') as day,$table.oid,sum(looknum) AS looknum,sum(playernum) AS playernum,sum(outnum)  AS outnum,sum(outnum)  AS scannum,sum(scannum)  AS scannum,sum(lovenum)  AS lovenum")
            ->where("belong", '=', $alias)
            ->groupBy("$table.oid")
            ->get();

        return $data;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = ['A1:A3', 'B1:F2', 'G1:K2'];

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
                    ->getStyle('A1:K' . '3')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                //冻结表头
                $event->sheet->getDelegate()->freezePane('B4');
            }
        ];
    }

}