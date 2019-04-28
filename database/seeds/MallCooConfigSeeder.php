<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MallCooConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'marketid' => 261,
                'mallcoo_mall_id' => '',
                'mallcoo_appid' => '5c8f325888ce7e0d78b8f537',
                'mallcoo_public_key' => 'ZCHg5m',
                'mallcoo_private_key' => 'd021b4c9d6c5c7ea',
            ],
            [
                'marketid' => 214,
                'mallcoo_mall_id' => '',
                'mallcoo_appid' => '5c04993d88ce7e3f14b6c8d1',
                'mallcoo_public_key' => 'QV-j4C',
                'mallcoo_private_key' => 'ccb250b11e99dfa7',
            ],
        ];

        foreach ($data as $item) {
            DB::table('mallcoo_config')->insert($item);
        }
    }
}
