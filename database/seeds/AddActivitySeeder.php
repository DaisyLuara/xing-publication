<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Resource\V1\Models\Activity;

class AddActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Activity::create(['utm_campaign' => 'Love520Action', 'name' => '520全城告白']);
    }
}
