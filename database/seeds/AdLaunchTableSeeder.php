<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class AdLaunchTableSeeder extends Seeder
{
    public function run()
    {
        $fileSystem = new Filesystem();
        $database = $fileSystem->get(base_path('database/seeds') . '/' . 'ar_avr_ad_oid.sql');
        DB::connection()->getPdo()->exec($database);
    }
}