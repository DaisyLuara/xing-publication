<?php

namespace App\Exports;


use App\Models\FaceCount;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use DB;

class PointExport implements FromCollection,WithStrictNullComparison,WithEvents
{
    public function __construct($oid,$date)
    {
        $this->oid=$oid;
        $this->date=$date;
    }

    public function collection()
    {
        $date=(new Carbon($this->date))->toDateString();
        $startDate=new Carbon($date);

        $facecount=DB::connection('ar')->table('face_count_log')
            ->join('ar_product_list','face_count_log.belong','=','ar_product_list.versionname')
            ->whereRaw("date_format(fcl.date, '%Y-%m') between '{$this->date}' and '{$this->date}' ")
            ->where('oid','=',$this->oid)
            ->where('belong','<>','all')
            ->groupBy(DB::raw("belong,date_format(face_count_log.date,'%Y-%m-%d')"));

    }

    public function registerEvents(): array
    {

    }
}