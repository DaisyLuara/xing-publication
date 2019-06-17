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
use Illuminate\Http\Request;

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

        activity('create_shop_role')
            ->causedBy($this->user())
            ->performedOn($role)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增召唤宝角色');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->syncPermissions($request->get('ids'));

        activity('update_shop_role')
            ->causedBy($this->user())
            ->performedOn($role)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑召唤宝角色');

        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Role $role, Request $request)
    {
        if ($role->users()->count() !== 0) {
            abort(403, '该角色已关联用户，暂不可删除');
        }
        $role->delete();

        activity('delete_shop_role')
            ->causedBy($this->user())
            ->performedOn($role)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => []])
            ->log('删除召唤宝角色');

        return $this->response()->noContent()->setStatusCode(204);
    }

}