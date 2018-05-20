<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ExportRequest;
use Excel;

class ExportController extends Controller
{
    public function store(ExportRequest $request)
    {
        $path = config('filesystems')['disks']['qiniu']['url'];
        $export = app($request->type);
        $fileName = $export->fileName . '_' . time() . '_' . '.' . 'xlsx';
        Excel::store($export, $fileName, 'qiniu');
        return $path . urlencode($fileName);
    }

}
