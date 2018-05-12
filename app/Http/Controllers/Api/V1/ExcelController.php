<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\ActiveHeaderExport;
use App\Exports\PointExport;
use App\Exports\ProjectExport;
use App\Exports\MarketingExport;
use Illuminate\Http\Request;
use Excel;

class ExcelController extends Controller
{
    public function marketingExcel(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        Excel::store(new MarketingExport($startDate, $endDate), '营销创意成果.xlsx');
    }

    public function projectExcel(Request $request)
    {
        Excel::store(new ProjectExport(), '节目数据统计.xlsx');
    }

    public function pointExcel(Request $request)
    {
        Excel::store(new PointExport($request), '点位数据统计.xlsx');
    }

    public function activeExcel(Request $request){
        Excel::store(new ActiveHeaderExport(),'动态表头测试.xlsx');
    }
}
