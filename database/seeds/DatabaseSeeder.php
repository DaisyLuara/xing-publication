<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env("APP_ENV") !== "production") {
            //基础数据填充
            $this->call(UsersTableSeeder::class);
            $this->call(TradeTableSeeder::class);

            //权限
            $this->call(AddPermissionAndRoleForDemandSeeder::class);
            $this->call(AddPermissionAndRoleForFinancepaySeeder::class);
            $this->call(AddPermissionAndRoleForPurchasingSeeder::class);
            $this->call(AddPermissionAndRoleForTeamSeeder::class);
            $this->call(AddWechatcardPermissonToSpecialUserSeeder::class);
            $this->call(AddProcessStaffSeeder::class);
            $this->call(AddPermissonForDesignerSeeder::class);
            //扩展权限-更新
            $this->call(UpdatePermissionSeeder::class);

            //召唤宝-权限
            $this->call(AddRoleForShopSeeder::class);
            $this->call(UpdatePermissionForShopSeeder::class);
            $this->call(AddRolePermissionToShop::class);


            //商品库存管理
            $this->call(ErpAttributesTableSeeder::class);
            $this->call(ModifyGoodServicesSeeder::class);
        }
    }
}
