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
        $user = $this->user();
        $companyID = $user->company_id;
        $ar_user_id = $user->ar_user_id;
        /** @var  $file \Illuminate\Http\UploadedFile */
        $file = $request->file;

        $width = 0;
        $height = 0;

        if ($request->type == 'image') {
            $image = Image::make($file);
            $height = $image->height();
            $width = $image->width();
        }

        $url = $uploader->save($file, "contract/" . str_plural($request->type));

        $data = [
            'size' => $file->getSize(),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
            'url' => $url,
            'company_id' => $companyID,
            'height' => $height,
            'width' => $width,
        ];

        $media->fill($data)->save();

        return $this->response->item($media, new MediaTransformer());
    }

    public function update(Request $request)
    {
        //
    }
}
