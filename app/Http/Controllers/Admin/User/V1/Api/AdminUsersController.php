<?php

namespace App\Http\Controllers\Admin\User\V1\Api;

use App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Admin\User\V1\Request\UserRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\CreateAdminStaffJob;

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

        if ($request->has('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role_id);
            });
        }

        $users = $query->whereHas('roles', function ($q) use ($isSuperAdmin) {
            if (!$isSuperAdmin) {
                $q->where('name', '<>', 'super-admin');
            }
        })->orderByDesc('id')->paginate(10);

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
        if ($role === null) {
            return $this->response->errorNotFound('角色不存在');
        }

        if (($role->name === 'legal-affairs' || $role->name === 'user') && !$request->parent_id) {
            abort(500, '请选择所属主管');
        }

        /** @var User $user */
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'parent_id' => $request->parent_id
        ]);

        $user->assignRole($role);

        if ($role->name === 'bd-manager' || $role->name === 'legal-affairs-manager') {
            $user->parent_id = $user->id;
            $user->update();
        }
        activity('user')
            ->causedBy($this->user())
            ->on($user)
            ->withProperties($request->except(['password']))
            ->log('新增用户');

        CreateAdminStaffJob::dispatch($user, $role)->onQueue('create_admin_staff');

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

        $attributes = $request->only(['name', 'phone', 'parent_id']);
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

    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $currentUser = $this->user();

        if ($currentUser->id == $user_id) {
            abort(403);
        }

        $user->delete();
        return $this->response->noContent();
    }

    public function syncZValue($user_id)
    {
        $user = $this->getUserByID($user_id);
        abort_if(!$user, 404, '用户不存在！');

        $zValue = ArMemberPermission::query()->where('mobile', $user->phone)->first(['z']);
        abort_if(!$zValue, 404, '请联系星动力系统管理员分配Z值!');

        $user->update($zValue->toArray());

        return $this->response->noContent();

    }
}
