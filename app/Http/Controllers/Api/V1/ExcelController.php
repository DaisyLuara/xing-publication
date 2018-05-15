<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\ProjectExport;
use Illuminate\Http\Request;
use Excel;

class ExcelController extends Controller
{
    public function export(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        Excel::store(new ProjectExport($startDate, $endDate), 'project.xlsx');
        return $this->response->array('success');
    }
}
