<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class ProjectLaunchTableSeeder extends Seeder
{
    public function run()
    {
        $fileSystem = new Filesystem();
        $database = $fileSystem->get(base_path('database/seeds') . '/' . 'ar_istar_tv_oid.sql');
        DB::connection()->getPdo()->exec($database);
    }
}