<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class UpdatePermissionForShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->where('guard_name', 'shop')->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['market_owner', 'activity_vip', 'ad_owner'];
        foreach ($roles as $item) {
            Role::findByName($item)->update(['guard_name' => 'shop']);
        }

        $permsData = [
            ['name' => 'shop_account', 'display_name' => '账户'],
            ['name' => 'shop_ad', 'display_name' => '广告'],
            ['name' => 'shop_resource', 'display_name' => '资源'],
            ['name' => 'shop_point', 'display_name' => '点位'],
            ['name' => 'shop_report', 'display_name' => '数据'],
            ['name' => 'shop_coupon', 'display_name' => '核销'],
        ];
        foreach ($permsData as $item) {
            Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'guard_name' => 'shop']);
        }

        #账户
        $account = Permission::findByName('shop_account', 'shop');
        $accountSecondData = [
            ['name' => 'shop_account.datum', 'display_name' => '我的资料']
        ];
        foreach ($accountSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $account->id, 'guard_name' => 'shop']);
            $this->createThirdPermission($obj, $item);
        }

        #广告
        $ad = Permission::findByName('shop_ad', 'shop');
        $adSecondData = [
            ['name' => 'shop_ad.market', 'display_name' => '授权列表'],
            ['name' => 'shop_ad.list', 'display_name' => '广告列表']
        ];
        foreach ($adSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $ad->id, 'guard_name' => 'shop']);
            $this->createThirdPermission($obj, $item);
        }

        #资源
        $resource = Permission::findByName('shop_resource', 'shop');
        $resourceSecondData = [
            ['name' => 'shop_resource.picture', 'display_name' => '图片管理'],
            ['name' => 'shop_resource.video', 'display_name' => '视频管理']
        ];
        foreach ($resourceSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $resource->id, 'guard_name' => 'shop']);
            $this->createThirdPermission($obj, $item);
        }

        #点位
        $point = Permission::findByName('shop_point', 'shop');
        $pointSecondData = [
            ['name' => 'shop_point.list', 'display_name' => '点位列表'],
        ];
        foreach ($pointSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $point->id, 'guard_name' => 'shop']);
            $this->createThirdPermission($obj, $item);
        }

        #数据
        $report = Permission::findByName('shop_report', 'shop');
        $reportSecondData = [
            ['name' => 'shop_report.detail', 'display_name' => '数据管理'],
        ];
        foreach ($reportSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $report->id, 'guard_name' => 'shop']);
            $this->createThirdPermission($obj, $item);
        }

        #核销
        $verify = Permission::findByName('shop_coupon', 'shop');
        $verifySecondData = [
            ['name' => 'shop_coupon.list', 'display_name' => '核销列表'],
            ['name' => 'shop_coupon.rules', 'display_name' => '优惠券规则'],
        ];
        foreach ($verifySecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $verify->id, 'guard_name' => 'shop']);
            $this->createThirdPermission($obj, $item);
        }
    }

    public function createThirdPermission($obj, $item)
    {
        $data = [
            ['name' => $item['name'] . '.read', 'display_name' => '查看'],
            ['name' => $item['name'] . '.create', 'display_name' => '新增'],
            ['name' => $item['name'] . '.update', 'display_name' => '修改'],
            ['name' => $item['name'] . '.delete', 'display_name' => '删除'],
        ];
        foreach ($data as $aa) {
            Permission::create(array_merge($aa, ['parent_id' => $obj->id, 'guard_name' => 'shop']));
        }

    }

}
