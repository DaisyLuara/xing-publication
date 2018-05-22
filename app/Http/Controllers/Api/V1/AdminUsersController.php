<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\V1\UserRequest;


class AdminUsersController extends Controller
{

    public function show($user_id)
    {
        return $this->response->item($this->getUserByID($user_id), new UserTransformer());
    }

    public function index(Request $request, User $user)
    {
        $isSuperAdmin = $this->user()->isSuperAdmin();

        $query = $user->query();
        if ($request->has('phone')) {
            $query->where('phone', 'like', $request->phone . '%');
        }

        if ($request->has('name')) {
            $query->where('name', 'like', $request->name . '%');
        }

        $users = $query->whereHas('roles', function ($q) use ($isSuperAdmin) {
            if (!$isSuperAdmin) {
                $q->where('name', '<>', 'super-admin');
            }
        })->paginate(10);

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

        activity('user')
            ->causedBy($this->user())
            ->on($user)
            ->withProperties($request->except(['password']))
            ->log('新增用户');

        //@todo 关联创建EXE LOOK 用户
        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }

    public function update($user_id, UserRequest $request)
    {


        $user = $this->getUserByID($user_id);
        $currentUser = $this->user();

        //传入的权限不会超过管理员本身权限
        $role = $currentUser->getSystemRoles()->firstWhere('id', $request->role_id);
        if ($request->role_id && $role) {
            $user->syncRoles($role);
        }

        $attributes = $request->only(['name', 'phone']);
        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        if ($request->password) {
            $attributes['password'] = bcrypt($request->password);
        }

        $user->update($attributes);

        activity('user')
            ->causedBy($currentUser)
            ->on($user)
            ->withProperties($request->except(['password']))
            ->log('修改用户');

        return $this->response->item($user, new UserTransformer());
    }

    private function getUserByID($user_id)
    {
        $isSuperAdmin = $this->user()->isSuperAdmin();

        $query = User::query();
        return $query->whereHas('roles', function ($q) use ($isSuperAdmin) {
            if (!$isSuperAdmin) {
                $q->where('name', '<>', 'super-admin');
            }
        })->where('id', '=', $user_id)->first();
    }
}
