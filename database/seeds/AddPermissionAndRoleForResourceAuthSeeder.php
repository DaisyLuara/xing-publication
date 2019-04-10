<?php

use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use Illuminate\Database\Seeder;

class AddPermissionAndRoleForResourceAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //资源授权
        $resource_auth = Permision::query()->where('name', '=', 'resource_auth')->first()
            ?? Permission::create(['name' => 'resource_auth', 'display_name' => '资源授权']);
        $project_auth = Permission::query()->where('name', '=', 'resource_auth.project_auth')->first()
            ?? Permission::create(['name' => "resource_auth.project_auth", 'display_name' => "节目授权", 'parent_id' => $resource_auth->id]);

        $data = [
            ['name' => 'resource_auth.project_auth.read', 'display_name' => '查看', 'parent_id' => $project_auth->id],
            ['name' => 'resource_auth.project_auth.create', 'display_name' => '新增', 'parent_id' => $project_auth->id],
            ['name' => 'resource_auth.project_auth.update', 'display_name' => '修改', 'parent_id' => $project_auth->id],
            ['name' => 'resource_auth.project_auth.delete', 'display_name' => '删除', 'parent_id' => $project_auth->id],
        ];
        foreach ($data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        //查看权限
        $readPermissionData = [
            "resource_auth",
            "resource_auth.project_auth",
            "resource_auth.project_auth.read",
        ];


        //创建与编辑
        $editPermissionData = [
            'resource_auth.project_auth.create',
            'resource_auth.project_auth.update',
        ];

        //删除权限
        $deletePermissionData = [
            'resource_auth.project_auth.delete',
        ];

        $admin = Role::query()->where('name', '=', 'admin')->first()
            ?? Role::create(['name' => 'admin', 'display_name' => '管理员']); //管理员
        $operation = Role::query()->where('name', '=', 'operation')->first()
            ?? Role::create(['name' => 'operation', 'display_name' => '平台运营']); //平台运营

        $admin->givePermissionTo(array_merge($readPermissionData, $editPermissionData, $deletePermissionData));
        $operation->givePermissionTo(array_merge($readPermissionData, $editPermissionData, $deletePermissionData));
    }
}
