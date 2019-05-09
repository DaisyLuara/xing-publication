<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/9
 * Time: 上午10:47
 */

namespace App\Http\Controllers\Admin\Company\V1\Api;


use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use App\Http\Controllers\Admin\Privilege\V1\Request\RoleRequest;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleDetailTransformer;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleTransformer;
use App\Http\Controllers\Controller;

class CompanyRoleController extends Controller
{
    public function show(Role $role)
    {
        return $this->response()->item($role, new RoleDetailTransformer())->setStatusCode(200);
    }

    public function index(Role $role)
    {
        $query = $role->query();
        $roles = $query->where('guard_name', 'shop')->paginate(10);
        return $this->response()->paginator($roles, new RoleTransformer())->setStatusCode(200);
    }

    public function store(RoleRequest $request, Role $role)
    {
        $role->fill(array_merge($request->all(), ['guard_name' => 'shop']))->save();
        $ids = $request->get('ids');
        $role->givePermissionTo($ids);

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->syncPermissions($request->get('ids'));
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() !== 0) {
            abort(403, '该角色已关联用户，暂不可删除');
        }
        $role->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }

}