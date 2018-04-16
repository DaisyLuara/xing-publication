<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)->times(2)->make();
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();
        User::insert($user_array);
    }
}
