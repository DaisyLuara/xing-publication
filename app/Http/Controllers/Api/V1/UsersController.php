<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Image;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\V1\UserRequest;

class UsersController extends Controller
{
    public function me()
    {
        //$this->user() dingo api helper trait
        return $this->response->item($this->user(), new UserTransformer());
    }


    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'phone']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        if ($request->password) {
            $attributes['password'] = bcrypt($request->password);
        }

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }
}
