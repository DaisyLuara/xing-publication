<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/21
 * Time: 下午4:54
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Api;


use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use App\Http\Controllers\Admin\Privilege\V1\Request\RoleRequest;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleDetailTransformer;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function show(Role $role)
    {
        return $this->response()->item($role, new RoleDetailTransformer())->setStatusCode(200);
    }

    public function index(Role $role)
    {
        $query = $role->query();
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->isSuperAdmin()) {
            $query->where('name', '<>', 'super-admin');
        }
        $roles = $query->where('guard_name', 'web')->paginate(10);
        return $this->response()->paginator($roles, new RoleTransformer())->setStatusCode(200);
    }

    public function store(RoleRequest $request, Role $role)
    {
        $role->fill($request->all())->save();
        $ids = $request->get('ids');
        $role->givePermissionTo($ids);

        activity('create_role')
            ->causedBy($this->user())
            ->performedOn($role)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增角色');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->syncPermissions($request->get('ids'));

        activity('update_role')
            ->causedBy($this->user())
            ->performedOn($role)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('更新角色');

        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Role $role, Request $request)
    {
        if ($role->users()->count() !== 0) {
            abort(403, '该角色已关联用户，暂不可删除');
        }
        $this->checkRole($role);
        $role->delete();


        activity('delete_role')
            ->causedBy($this->user())
            ->performedOn($role)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('删除角色');

        return $this->response()->noContent()->setStatusCode(204);
    }

    private function checkRole(Role $role): void
    {
        if ($role->name ==='admin' || $role->name === 'super-admin') {
            abort(403, '管理员角色不可变更');
        }
    }
}