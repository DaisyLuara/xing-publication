<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class AddRolePermissionToShop extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $parentNodes = [
            ['name' => 'shop_wechat', 'display_name' => '授权', 'children' => [
                ['name' => 'shop_wechat.wechat', 'display_name' => '授权'],
                ['name' => 'shop_wechat.public', 'display_name' => '通行证'],
            ]],
            ['name' => 'shop_project', 'display_name' => '智造', 'children' => [
                ['name' => 'shop_project.project', 'display_name' => '标准节目'],
            ]],
            ['name' => 'shop_prize', 'display_name' => '奖品', 'children' => [
                ['name' => 'shop_prize.coupon_batch', 'display_name' => '奖品列表'],
                ['name' => 'shop_prize.coupon_batch_policy', 'display_name' => '奖品模板'],
                ['name' => 'shop_prize.coupon', 'display_name' => '奖品核销'],
            ]],
            ['name' => 'shop_launch', 'display_name' => '投放', 'children' => [
                ['name' => 'shop_launch.wechat', 'display_name' => '授权链接投放'],
                ['name' => 'shop_launch.project', 'display_name' => '节目投放'],
                ['name' => 'shop_launch.project_tpl', 'display_name' => '节目投放模板'],
                ['name' => 'shop_launch.prize', 'display_name' => '奖品投放'],
            ]],
        ];

        foreach ($parentNodes as $parentNode) {
            $name = $parentNode['name'];
            DB::table('permissions')->whereRaw("name like '$name%'")->delete();
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($parentNodes as $parentNode) {
            $parentNodeDB = Permission::create(['name' => $parentNode['name'], 'display_name' => $parentNode['display_name'], 'guard_name' => 'shop']);
            Log::info('parent_node->' . $parentNode['name']);

            foreach ($parentNode['children'] as $childrenNode) {
                Log::info('child_node-->' . $childrenNode['name']);
                $childrenNodeDB = Permission::create(['name' => $childrenNode['name'], 'display_name' => $childrenNode['display_name'], 'parent_id' => $parentNodeDB->id, 'guard_name' => 'shop']);
                $grandSonNodes = [
                    ['name' => $childrenNode['name'] . '.read', 'display_name' => '查看'],
                    ['name' => $childrenNode['name'] . '.create', 'display_name' => '新增'],
                    ['name' => $childrenNode['name'] . '.update', 'display_name' => '修改'],
                    ['name' => $childrenNode['name'] . '.delete', 'display_name' => '删除'],
                ];
                foreach ($grandSonNodes as $grandSonNode) {
                    Log::info('grandson_node--->' . $grandSonNode['name']);
                    Permission::create(array_merge($grandSonNode, ['parent_id' => $childrenNodeDB->id, 'guard_name' => 'shop']));
                }
            }

        }

        $marketer = Role::query()->updateOrCreate(['name' => 'market_owner'], ['name' => 'market_owner', 'display_name' => '场地主']);

        $marketer->syncPermissions(['shop_account', 'shop_point', 'shop_report', 'shop_wechat', 'shop_project', 'shop_prize', 'shop_launch']);
    }
}
