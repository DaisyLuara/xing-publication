<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Auditing\V1\Models\Activity;

class AddActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Activity::create(['name' => '520全城告白']);
    }
}
