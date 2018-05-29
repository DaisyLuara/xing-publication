<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Transformer\ImageTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\ImageRequest;
use App\Http\Controllers\Admin\Common\V1\Models\Image;
use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Controller;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        $user = $this->user();

        $result = $uploader->save($request->image, str_plural($request->type));

        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();

        return $this->response->item($image, new ImageTransformer())->setStatusCode(201);
    }
}
