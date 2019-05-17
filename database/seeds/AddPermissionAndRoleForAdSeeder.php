<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class AddPermissionAndRoleForAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //广告
        $ad = Permission::query()->where('name','=','ad')->first()
            ?? Permission::create(['name' => 'ad', 'display_name' => '广告']);
        //广告投放
        $item =Permission::query()->where('name','=','ad.item')->first()
            ?? Permission::create(['name' => 'ad.item', 'display_name' => '广告投放', 'parent_id' => $ad->id]);
        //广告方案
        $plan = Permission::query()->where('name','=','ad.plan')->first()
            ?? Permission::create(['name' => 'ad.plan', 'display_name' => '广告方案', 'parent_id' => $ad->id]);

        $data = [
            ['name' => 'ad.item.read', 'display_name' => '查看', 'parent_id' => $item->id],
            ['name' => 'ad.item.create', 'display_name' => '新增', 'parent_id' => $item->id],
            ['name' => 'ad.item.update', 'display_name' => '修改', 'parent_id' => $item->id],

            ['name' => 'ad.plan.read', 'display_name' => '查看', 'parent_id' => $plan->id],
            ['name' => 'ad.plan.create', 'display_name' => '新增', 'parent_id' => $plan->id],
            ['name' => 'ad.plan.update', 'display_name' => '修改', 'parent_id' => $plan->id],
        ];

        foreach ($data as $item) {
            if (!Permission::query()->where('name','=',$item['name'])->first()) {
                Permission::create($item);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        //查看权限
        $readPermissionData = [
            'ad',
            'ad.item',
            'ad.plan',
            'ad.item.read',
            'ad.plan.read',
        ];


        //创建与编辑
        $editPermissionData = [
            'ad.item.create',
            'ad.item.update',

            'ad.plan.create',
            'ad.plan.update',
        ];


        /** @var Role $admin */
        $admin = Role::query()->where('name','=','admin')->first();

        $admin->givePermissionTo(array_merge($readPermissionData, $editPermissionData));
    }
}
