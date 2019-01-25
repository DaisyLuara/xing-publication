<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/9
 * Time: 下午1:59
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Transformer;


use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class RoleDetailTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'display_name' => $role->display_name,
            'permission' => $role->permissions->toArray(),
            'created_at' => $role->created_at->toDateString(),
            'updated_at' => $role->updated_at->toDateString(),
        ];
    }
}