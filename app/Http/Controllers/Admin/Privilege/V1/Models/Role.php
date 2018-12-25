<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/24
 * Time: 下午5:25
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name'
    ];

    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id')->select('id', 'display_name', 'parent_id', 'lft', 'rgt', 'depth');
    }
}