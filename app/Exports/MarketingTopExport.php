<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/29
 * Time: 17:24
 */

namespace App\Exports;

use DB;


class MarketingTopExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->fileName = '营销创意成果';
    }

    public function collection()
    {
        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->whereRaw("date_format(fcl.date, '%Y-%m-%d') BETWEEN '{$this->startDate}' AND '{$this->endDate}'")
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy(DB::raw("oid,belong"))
            ->orderBy('apl')
            ->orderBy('looknum', 'desc')
            ->selectRaw("apl.name as name ,sum(looknum) as lookNum ,sum(playernum) as playerNum ,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum");

        $faceCount1 = DB::connection('ar')->table(DB::raw("{$faceCount->toSql()}) as a,(select @gn := 0) as b"))
            ->selectRaw("  @gn := case when @name = name then @gn + 1 else 1 end gn,@name := name name,looknum,playernum,lovenum,outnum,scannum");

        $faceCount2 = DB::connection('ar')->table(DB::raw("{$faceCount1->toSql()} as c"))
            ->selectRaw("name,sum(looknum),sum(playernum),sum(lovenum),sum(outnum),sum(scannum)")
            ->where('gn', '<=', 100)
            ->groupBy('name')
            ->get();
        dd($faceCount2);

    }

    public function registerEvents(): array
    {
        return [];
    }

}