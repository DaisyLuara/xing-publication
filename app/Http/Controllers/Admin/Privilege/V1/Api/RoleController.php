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

class RoleController extends Controller
{
    public function show(Role $role)
    {
        return $this->response()->item($role, new RoleDetailTransformer());
    }

    public function index(Role $role)
    {
        $query = $role->query();
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->isSuperAdmin()) {
            $query->where("name", '<>', 'super-admin');
        }
        $roles = $query->where('guard_name', 'web')->paginate(10);
        return $this->response()->paginator($roles, new RoleTransformer());
    }

    public function store(RoleRequest $request, Role $role)
    {
        $role->fill($request->all())->save();
        $ids = $request->ids;
        $role->givePermissionTo($ids);

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $this->checkRole($role);
        $role->update($request->all());
        $role->syncPermissions($request->ids);
        return $this->response()->noContent();
    }

    public function destroy(Role $role)
    {
        $this->checkRole($role);
        $role->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }

    private function checkRole(Role $role)
    {
        if ($role->name == 'admin' || $role->name == 'super-admin') {
            abort(403, '管理员角色不可变更');
        }
    }
}