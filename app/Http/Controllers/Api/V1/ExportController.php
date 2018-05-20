<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ExportRequest;
use Excel;

class ExportController extends Controller
{
    public function store(ExportRequest $request)
    {
        $export = app($request->type);
        return Excel::download($export, $export->fileName);
    }

}
