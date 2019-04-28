<?php

namespace App\Http\Controllers\Admin\Media\V1\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Admin\Media\V1\Models\ActivityMedia;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Media\V1\Request\MediaRequest;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;

class MediaController extends Controller
{

    public function store(MediaRequest $request, Media $media, ImageUploadHandler $uploader): \Dingo\Api\Http\Response
    {
        /** @var  $file \Illuminate\Http\UploadedFile */
        $file = $request->get('file');

        $width = 0;
        $height = 0;

        if ($request->type === 'image') {
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

        return $this->response()->item($media, new MediaTransformer())->setStatusCode(201);
    }

    public function create(Request $request, Media $media): \Dingo\Api\Http\Response
    {
        $disk = \Storage::disk('qiniu');
        $domain = $disk->getDriver()->downloadUrl();
        $data = [
            'name' => $request->get('name'),
            'url' => $domain . urlencode($request->get('key')),
            'size' => $request->get('size'),
            'height' => 0,
            'width' => 0,
        ];
        $media->fill($data)->save();
        return $this->response()->item($media, new MediaTransformer())->setStatusCode(201);
    }

    /**
     * 活动media存储
     * @param Request $request
     * @param ActivityMedia $media
     * @return \Dingo\Api\Http\Response
     */
    public function activityMediaCreate(Request $request, ActivityMedia $media): \Dingo\Api\Http\Response
    {
        $disk = \Storage::disk('qiniu_yq');
        $domain = $disk->getDriver()->downloadUrl();
        $data = [
            'name' => $request->get('name'),
            'url' => $domain . urlencode($request->get('key')),
            'size' => $request->get('size'),
        ];
        $media->fill($data)->save();
        //七牛鉴定
//        MediaCheckJob::dispatch($media)->onQueue('data-clean');
        return $this->response()->noContent()->setStatusCode(201);
    }

}
