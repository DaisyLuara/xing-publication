<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_geo')->insert([
            ['lng' => 121.529747, 'lat' => 31.216071],
            ['lng' => 121.53498, 'lat' => 31.21489],
            ['lng' => 121.535303, 'lat' => 31.212728],
            ['lng' => 121.539525, 'lat' => 31.215631],
            ['lng' => 121.530596, 'lat' => 31.218271],
            ['lng' => 121.539777, 'lat' => 31.212666],
            ['lng' => 121.539777, 'lat' => 31.212666],
            ['lng' => 121.524577, 'lat' => 31.210952],
            ['lng' => 121.524577, 'lat' => 31.210952]
        ]);
    }
}
