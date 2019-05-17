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
        Activity::query()->truncate();

        Activity::create(['utm_campaign' => 'Love520Action', 'name' => '520全城告白']);
        Activity::create(['utm_campaign' => 'wuyue_invitation', 'name' => '吾悦邀请函']);
        Activity::create(['utm_campaign' => 'Love520JinTie', 'name' => '520近铁广场']);
    }
}
