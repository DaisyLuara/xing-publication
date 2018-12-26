<?php

use Illuminate\Database\Seeder;

class ModifyGoodServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('goods_services')->where('id', 4)
            ->update(['name' => '申跃：43寸立式双屏室内机 ']);
        DB::table('goods_services')->where('id', 5)
            ->update(['name' => '申跃：32寸双屏立式室内机']);
        DB::table('goods_services')->where('id', 6)
            ->update(['name' => '申跃：43寸立式室内机']);
        DB::table('goods_services')->where('id', 7)
            ->update(['name' => '卓有：星视度智能互动一体机']);
        DB::table('goods_services')->where('id', 8)
            ->update(['name' => '域展：智能互动屏']);
        DB::table('goods_services')->where('id', 9)
            ->update(['name' => '域展：星视度智能互动一体机']);
        DB::table('goods_services')->where('id', 10)
            ->delete();
        DB::table('goods_services')->where('id', 11)
            ->delete();
    }
}
