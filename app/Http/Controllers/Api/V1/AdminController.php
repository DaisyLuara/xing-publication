<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\V1\UserRequest;


class AdminController extends Controller
{

    public function show($user_id)
    {
        $isSuperAdmin = $this->user()->isSuperAdmin();

        $query = User::query();
        $user = $query->whereHas('roles', function ($q) use ($isSuperAdmin) {
            if (!$isSuperAdmin) {
                $q->where('name', '<>', 'super-admin');
            }
        })->where('id', '=', $user_id)->first();

        return $this->response->item($user, new UserTransformer());
    }

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
        //保证传入的角色不会超过本身的权限
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

    public function update(UserRequest $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->user();

        $attributes = $request->only(['name', 'phone', 'password']);

        //传入的权限不会超过本身权限
        $role = $currentUser->getSystemRoles()->firstWhere('id', $request->role_id);
        if ($request->role_id && $role) {
            $currentUser->syncRoles($role);
        }

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $currentUser->update($attributes);

        return $this->response->item($currentUser, new UserTransformer());
    }
}
