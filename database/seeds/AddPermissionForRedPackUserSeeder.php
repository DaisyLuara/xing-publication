<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddPermissionForRedPackUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'activity', 'display_name' => '活动']);
        $redPackUser = User::query()->where('phone', 13818403072)->first();
        $redPackUser->givePermissionTo('activity');
    }
}
