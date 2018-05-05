<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\V1\UserRequest;

class UsersController extends Controller
{

    public function index(Request $request, User $user)
    {
        $isSuperAdmin = $this->user()->isSuperAdmin();

        $query = $user->query();
        if ($request->has('phone')) {
            $query->where('phone', 'like', $request->phone . '%');
        }

        $users = $query->whereHas('roles', function ($q) use ($isSuperAdmin) {
            if (!$isSuperAdmin) {
                $q->where('name', '<>', 'super-admin');
            }
        })->paginate(5);

        return $this->response->paginator($users, new UserTransformer());
    }


    /**
     * 管理员创建用户
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $role = $this->user()->getSystemRoles()->firstWhere('id', $request->role_id);
        if (is_null($role)) {
            return $this->response->errorNotFound('角色不存在');
        }

        /** @var User $user */
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($role);

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

        $attributes = $request->only(['name', 'phone', 'password', 'role_id']);
        //@todo 修改用户角色

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }
}
