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
        $ad = Permission::query()->where('name', '=', 'ad')->first()
            ?? Permission::create(['name' => 'ad', 'display_name' => '广告']);
        //广告投放
        $item = Permission::query()->where('name', '=', 'ad.item')->first()
            ?? Permission::create(['name' => 'ad.item', 'display_name' => '广告投放', 'parent_id' => $ad->id]);
        //广告方案
        $plan = Permission::query()->where('name', '=', 'ad.plan')->first()
            ?? Permission::create(['name' => 'ad.plan', 'display_name' => '广告方案', 'parent_id' => $ad->id]);

        //广告素材
        $advertisement = Permission::query()->where('name', '=', 'ad.advertisement')->first()
            ?? Permission::create(['name' => 'ad.advertisement', 'display_name' => '广告素材', 'parent_id' => $ad->id]);

        //广告行业compo
        $trade = Permission::query()->where('name', '=', 'ad.trade')->first()
            ?? Permission::create(['name' => 'ad.trade', 'display_name' => '广告行业', 'parent_id' => $ad->id]);


        $data = [
            ['name' => 'ad.item.read', 'display_name' => '查看', 'parent_id' => $item->id],
            ['name' => 'ad.item.create', 'display_name' => '新增', 'parent_id' => $item->id],
            ['name' => 'ad.item.update', 'display_name' => '修改', 'parent_id' => $item->id],

            ['name' => 'ad.plan.read', 'display_name' => '查看', 'parent_id' => $plan->id],
            ['name' => 'ad.plan.create', 'display_name' => '新增', 'parent_id' => $plan->id],
            ['name' => 'ad.plan.update', 'display_name' => '修改', 'parent_id' => $plan->id],

            ['name' => 'ad.advertisement.read', 'display_name' => '查看', 'parent_id' => $advertisement->id],
            ['name' => 'ad.advertisement.create', 'display_name' => '新增', 'parent_id' => $advertisement->id],
            ['name' => 'ad.advertisement.update', 'display_name' => '修改', 'parent_id' => $advertisement->id],

            ['name' => 'ad.trade.read', 'display_name' => '查看', 'parent_id' => $trade->id],
            ['name' => 'ad.trade.create', 'display_name' => '新增', 'parent_id' => $trade->id],
            ['name' => 'ad.trade.update', 'display_name' => '修改', 'parent_id' => $trade->id],
        ];

        foreach ($data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        //查看权限
        $readPermissionData = [
            'ad',
            'ad.item',
            'ad.plan',
            'ad.advertisement',
            'ad.trade',

            'ad.item.read',
            'ad.plan.read',
            'ad.advertisement.read',
            'ad.trade.read',
        ];


        //创建与编辑
        $editPermissionData = [
            'ad.item.create',
            'ad.item.update',

            'ad.plan.create',
            'ad.plan.update',

            'ad.advertisement.create',
            'ad.advertisement.update',

            'ad.trade.create',
            'ad.trade.update',
        ];


        /** @var Role $admin */
        $admin = Role::query()->where('name', '=', 'admin')->first();

        $admin->givePermissionTo(array_merge($readPermissionData, $editPermissionData));
    }
}
