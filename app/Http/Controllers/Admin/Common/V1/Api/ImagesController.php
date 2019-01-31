<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Transformer\ImageTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\ImageRequest;
use App\Http\Controllers\Admin\Common\V1\Models\Image;
use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use Log;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image)
    {

        $userID = 0;
        if ($request->sign) {
            try {
                $userID = decrypt($request->sign);
            } catch (DecryptException $exception) {
                Log::info('decrypt fail', [$exception->getMessage()]);
            }
        }
        Log::info('image_request', $request->all());
        $path = $uploader->save($request->image, str_plural($request->type));

        $image->path = $path;
        $image->type = $request->type;
        $image->user_id = $userID;
        $image->save();

        return $this->response->item($image, new ImageTransformer())->setStatusCode(201);
    }
}
