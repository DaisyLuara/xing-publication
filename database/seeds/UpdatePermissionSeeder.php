<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class UpdatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permsData = Permission::all();
        Permission::query()->delete();
        app()['cache']->forget('spatie.permission.cache');
        foreach ($permsData as $item) {
            Permission::create(['name' => $item->name, 'display_name' => $item->display_name]);
        }

        #system二级菜单
        $system = Permission::findByName('system');
        $sysSecondData = [
            ['name' => 'system.user', 'display_name' => '用户管理'],
            ['name' => 'system.role', 'display_name' => '角色管理'],
            ['name' => 'system.permission', 'display_name' => '权限管理'],
        ];
        foreach ($sysSecondData as $item) {
            Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $system->id]);
        }

        #用户管理权限
        $sysUser = Permission::findByName('system.user');
        $sysUserData = [
            ['name' => 'system.user.read', 'display_name' => '查看'],
            ['name' => 'system.user.create', 'display_name' => '新增'],
            ['name' => 'system.user.update', 'display_name' => '修改'],
            ['name' => 'system.user.delete', 'display_name' => '删除'],
        ];
        foreach ($sysUserData as $item) {
            Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $sysUser->id]);
        }

        #角色管理权限
        $sysRole = Permission::findByName('system.role');
        $sysRoleData = [
            ['name' => 'system.role.read', 'display_name' => '查看'],
            ['name' => 'system.role.create', 'display_name' => '新增'],
            ['name' => 'system.role.update', 'display_name' => '修改'],
            ['name' => 'system.role.delete', 'display_name' => '删除'],
        ];
        foreach ($sysRoleData as $item) {
            Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $sysRole->id]);
        }

        #权限管理权限
        $sysPerm = Permission::findByName('system.permission');
        $sysPermData = [
            ['name' => 'system.permission.read', 'display_name' => '查看'],
            ['name' => 'system.permission.create', 'display_name' => '新增'],
            ['name' => 'system.permission.update', 'display_name' => '修改'],
            ['name' => 'system.permission.delete', 'display_name' => '删除']
        ];
        foreach ($sysPermData as $item) {
            Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $sysPerm->id]);
        }

        $allPermission = Permission::all();
        $superAdmin = Role::findByName('super-admin');
        $superAdmin->givePermissionTo($allPermission);

        $admin = Role::findByName('admin');
        $admin->givePermissionTo($allPermission);
    }
}
