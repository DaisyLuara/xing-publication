<?php

namespace App\Handlers;

use Log;

class ImageUploadHandler
{
    protected $allowed_ext = ['doc', 'docx', 'pdf', 'png', 'jpg', 'jpeg'];

    public function save($file, $request)
    {
        /** @var  $file \Illuminate\Http\UploadedFile */
        $format = $file->getClientOriginalExtension();
        if (!in_array($format, $this->allowed_ext)) {
            abort(500, "不支持" . $format . "文件格式");
        }
        $extension = strtolower($format);
        $file_prefix = str_plural($request->type);
        $disk = \Storage::disk('qiniu_yq');

        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;
        $result = $disk->put($filename, fopen($file, 'r'));
        Log::info('qi niu url', ['result' => $result]);

        return $disk->getDriver()->downloadUrl($filename)->getUrl();
    }

}