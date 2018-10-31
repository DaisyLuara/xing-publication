<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class AddPermissionForProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::query()->whereIn('phone', ['18301766780', '13764554970', '13601928127'])->get();
        $users->each(function ($user) {
            if (!$user->hasPermissionTo('auditing')) {
                $user->givePermissionTo('auditing');
            }
        });
    }
}
