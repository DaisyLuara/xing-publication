<?php

use Illuminate\Database\Seeder;

class ErpAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('erp_attributes')->insert([
            'name' => 'name',
            'display_name' => '产品名称'
        ]);
        DB::table('erp_attributes')->insert([
            'name' => 'color',
            'display_name' => '产品颜色'
        ]);
    }
}
