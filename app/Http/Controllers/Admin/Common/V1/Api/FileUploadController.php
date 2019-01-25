<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Models\FileUpload;
use App\Http\Controllers\Admin\Common\V1\Request\FileUploadRequest;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Psr7\parse_query;

class FileUploadController extends Controller
{
    public function show(FileUploadRequest $request)
    {
        $scene = urldecode($request->scene);
        $scene = str_replace('istar:', '', $scene);
        $id = explode('_', $scene)[0];
        $fileUpload = FileUpload::query()->findOrFail($id);

        abort_if(!$fileUpload->parms, 204);

        $params = parse_query($fileUpload->parms);
        return response()->json($params);

    }
}
