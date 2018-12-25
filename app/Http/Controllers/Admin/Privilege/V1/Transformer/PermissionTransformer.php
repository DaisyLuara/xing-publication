<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/21
 * Time: 下午4:59
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Transformer;


use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;

class PermissionTransformer extends TransformerAbstract
{
    public function transform(Permission $permission)
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
            'display_name' => $permission->display_name,
            'created_at' => $permission->created_at->toDateString(),
            'updated_at' => $permission->updated_at->toDateString(),
        ];
    }
}