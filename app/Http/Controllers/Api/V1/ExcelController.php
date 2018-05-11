<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\ProjectByMonthExport;
use App\Exports\ProjectExport;
use Illuminate\Http\Request;
use Excel;

class ExcelController extends Controller
{
    public function projectExcel(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        Excel::store(new ProjectExport($startDate, $endDate), 'project.xlsx');
        return $this->response->array('success');
    }

    public function projectMonthExcel(Request $request){
        Excel::store(new ProjectByMonthExport() ,'project_month.xlsx');
    }
}
