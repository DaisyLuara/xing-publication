<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 先创建权限
        Permission::create(['name' => 'company']);
        Permission::create(['name' => 'contract']);
        Permission::create(['name' => 'project']);
        Permission::create(['name' => 'system']);

        // 创建超级管理员角色，并赋予权限
        $superAdmin = Role::create(['name' => 'super-admin', 'display_name' => '系统管理员']);
        $superAdmin->givePermissionTo(['company', 'contract', 'system', 'project']);

        // 创建管理员角色，并赋予权限
        $admin = Role::create(['name' => 'admin', 'display_name' => '管理员']);
        $admin->givePermissionTo(['company', 'system', 'project']);

        // 创建普通用户
        $user = Role::create(['name' => 'user', 'display_name' => '普通用户']);
        $user->givePermissionTo(['company', 'project']);

        // 法务 公司资质审核&负责节目上传后的审批
        $auditor = Role::create(['name' => 'legal-affairs', 'display_name' => '法务']);
        $auditor->givePermissionTo(['project', 'company']);

    }

    public function down()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
