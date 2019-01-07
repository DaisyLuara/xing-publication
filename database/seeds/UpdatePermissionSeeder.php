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
        Permission::query()->delete();
        app()['cache']->forget('spatie.permission.cache');
        $permsData = [
            ['name' => 'company', 'display_name' => '公司'],
            ['name' => 'system', 'display_name' => '设置'],
            ['name' => 'contract', 'display_name' => '合同'],
            ['name' => 'project', 'display_name' => '节目'],
            ['name' => 'verify', 'display_name' => '审核'],
            ['name' => 'device', 'display_name' => '设备'],
            ['name' => 'ad', 'display_name' => '广告'],
            ['name' => 'point', 'display_name' => '点位'],
            ['name' => 'setting', 'display_name' => '配置'],
            ['name' => 'team', 'display_name' => '团队'],
            ['name' => 'report', 'display_name' => '数据'],
            ['name' => 'home', 'display_name' => '主页'],
            ['name' => 'download', 'display_name' => '下载'],
            ['name' => 'account', 'display_name' => '账户'],
            ['name' => 'resource', 'display_name' => '资源'],
            ['name' => 'invoice', 'display_name' => '票据'],
            ['name' => 'payments', 'display_name' => '付款'],
            ['name' => 'finance_bill', 'display_name' => '财务开票'],
            ['name' => 'finance_pay', 'display_name' => '财务付款'],
            ['name' => 'auditing', 'display_name' => '审批'],
            ['name' => 'wechat_card', 'display_name' => '微信卡券'],
        ];
        foreach ($permsData as $item) {
            Permission::create(['name' => $item['name'], 'display_name' => $item['display_name']]);
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
