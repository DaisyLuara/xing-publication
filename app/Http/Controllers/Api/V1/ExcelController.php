<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\MarketingExport;
use App\Exports\PointExport;
use App\Exports\ProjectExport;
use Excel;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function marketingExcel(Request $request)
    {
        Excel::store(new MarketingExport($request), '营销创意成果.xlsx');
    }

    public function projectExcel(Request $request)
    {
        Excel::store(new ProjectExport(), '节目数据统计.xlsx');
    }

    public function pointExcel(Request $request)
    {
        Excel::store(new PointExport($request), '点位数据统计.xlsx');
    }

}
