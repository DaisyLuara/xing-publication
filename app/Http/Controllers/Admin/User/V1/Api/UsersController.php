<?php

namespace App\Http\Controllers\Admin\User\V1\Api;

use App\Http\Controllers\Admin\User\V1\Transformer\LoginUserTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Admin\User\V1\Request\UserRequest;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * 登录获取用户信息
     * @return \Dingo\Api\Http\Response
     */
    public function me()
    {
        return $this->response()->item($this->user(), new LoginUserTransformer());
    }

    /**
     * 账号设置修改
     * @param UserRequest $request
     * @return mixed
     */
    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'phone']);

        if ($request->has('avatar_image_id')) {
            $image = Image::find($request->get('avatar_image_id'));

            $attributes['avatar'] = $image->path;
        }

        if ($request->has('password')) {
            $attributes['password'] = bcrypt($request->get('password'));
        }

        $user->update($attributes);

        return $this->response()->item($user, new UserTransformer());
    }
}
