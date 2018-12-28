<?php

namespace App\Http\Controllers\Admin\User\V1\Transformer;

use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles', 'permissions'];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'parent_id' => $user->parent_id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'tower_access_token' => (string)$user->tower_access_token,
            'bind_weixin' => $user->weixin_openid || $user->weixin_unionid,
            'ar_user_id' => $user->ar_user_id,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
            'pivot' => $user->pivot,
            'permissions' => $this->getUserPermission($user)
        ];
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }

//    public function includePermissions(User $user)
//    {
//        return $this->collection($user->getAllPermissions(), new PermissionTransformer());
//    }

    private function getUserPermission(User $user)
    {
        $permissions = $user->getAllPermissions();
        $permId = [];
        foreach ($permissions as $permission) {
            $permId[] = $permission->id;
        }
        return Permission::query()->whereIn('id', $permId)->selectRaw('id,name,parent_id')->get()->toHierarchy();
    }

}