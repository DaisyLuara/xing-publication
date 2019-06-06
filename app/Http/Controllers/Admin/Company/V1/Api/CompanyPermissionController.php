<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/9
 * Time: 上午10:47
 */

namespace App\Http\Controllers\Admin\Company\V1\Api;


use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Request\PermissionRequest;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\PermissionTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class CompanyPermissionController extends Controller
{
    public function show(Permission $permission)
    {
        return $this->response()->item($permission, new PermissionTransformer())->setStatusCode(200);
    }

    public function index()
    {
        $permission = Permission::query()->where('parent_id', 0)
            ->where('guard_name', 'shop')
            ->paginate(10);
        return $this->response()->paginator($permission, new PermissionTransformer())->setStatusCode(200);
    }

    public function store(PermissionRequest $request)
    {
        $permission = Permission::create(array_merge($request->all(), ['guard_name' => 'shop']));

        activity('create_shop_permission')
            ->causedBy($this->user())
            ->performedOn($permission)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增召唤宝权限');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        if ($request->has('parent_id')) {
            abort(403, '不可移动节点');
        }
        $permission->update($request->all());

        activity('update_shop_permission')
            ->causedBy($this->user())
            ->performedOn($permission)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑召唤宝权限');

        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Permission $permission,Request $request)
    {
        $permission->descendantsAndSelf()->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        activity('delete_shop_permission')
            ->causedBy($this->user())
            ->performedOn($permission)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => []])
            ->log('删除召唤宝权限');

        return $this->response()->noContent()->setStatusCode(204);
    }
}