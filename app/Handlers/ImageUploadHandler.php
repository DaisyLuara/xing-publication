<?php

namespace App\Handlers;

use Log;

class ImageUploadHandler
{
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function save($file, $file_prefix)
    {
        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        // 值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        $disk = \Storage::disk('qiniu');
        $result = $disk->put($filename, fopen($file, 'r'));
        Log::info('qi niu url', ['result' => $result]);

        $path = $disk->getDriver()->downloadUrl($filename)->getUrl();

        return ['path' => $path];

    }

}