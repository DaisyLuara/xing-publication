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

        $userID = 0;
        if ($request->sign) {
            $userID = decrypt($request->sign);
        }
        $path = $uploader->save($request->image, str_plural($request->type));

        $image->path = $path;
        $image->type = $request->type;
        $image->user_id = $userID;
        $image->save();

        return $this->response->item($image, new ImageTransformer())->setStatusCode(201);
    }
}
