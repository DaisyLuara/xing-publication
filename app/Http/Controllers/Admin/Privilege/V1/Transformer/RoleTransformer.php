<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/24
 * Time: 下午5:08
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Transformer;


use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'display_name' => $role->display_name,
            'permission' => $role->permission->toHierarchy(),
            'created_at' => $role->created_at->toDateString(),
            'updated_at' => $role->updated_at->toDateString(),
        ];
    }
}