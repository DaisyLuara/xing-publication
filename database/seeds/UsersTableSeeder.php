<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $fileSystem = new Filesystem();
        $database = $fileSystem->get(base_path('database/seeds') . '/' . 'init.sql');
        DB::connection()->getPdo()->exec($database);
    }
}