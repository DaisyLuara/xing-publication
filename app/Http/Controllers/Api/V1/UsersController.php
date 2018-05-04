<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Image;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\V1\UserRequest;

class UsersController extends Controller
{
    /**
     * 本系统不只允许管理员创建用户
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

//        $user->assignRole($role);

        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }

    public function me()
    {
        //$this->user() dingo api helper trait
        return $this->response->item($this->user(), new UserTransformer());
    }


    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }
}
