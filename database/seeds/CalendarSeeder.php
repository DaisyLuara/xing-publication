<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileSystem = new Filesystem();
        $database = $fileSystem->get(base_path('database/seeds') . '/' . 'Calendar.sql');
        DB::connection('ar')->getPdo()->exec($database);
    }
}
