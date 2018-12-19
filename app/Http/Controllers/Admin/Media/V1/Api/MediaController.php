<?php

namespace App\Http\Controllers\Admin\Media\V1\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Media\V1\Request\MediaRequest;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Handlers\ImageUploadHandler;
use Image;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;

class MediaController extends Controller
{

    public function store(MediaRequest $request, Media $media, ImageUploadHandler $uploader)
    {
        /** @var  $file \Illuminate\Http\UploadedFile */
        $file = $request->file;

        $width = 0;
        $height = 0;

        if ($request->type == 'image') {
            $image = Image::make($file);
            $height = $image->height();
            $width = $image->width();
        }

        $url = $uploader->save($file, $request);

        $data = [
            'size' => $file->getSize(),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
            'url' => $url,
            'height' => $height,
            'width' => $width,
        ];

        $media->fill($data)->save();

        return $this->response->item($media, new MediaTransformer());
    }

    public function create(Request $request, Media $media)
    {
        $disk = \Storage::disk('qiniu');
        $domain = $disk->getDriver()->downloadUrl();
        $data = [
            'name' => $request->name,
            'url' => $domain . urlencode($request->key),
            'size' => $request->size,
            'height' => 0,
            'width' => 0,
        ];
        $media->fill($data)->save();
        return $this->response->item($media, new MediaTransformer());
    }
}
