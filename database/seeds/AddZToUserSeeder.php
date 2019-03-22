<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class AddZToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Users = DB::connection('ar')->table('news_user_permission')->selectRaw('mobile,z')->get();
        foreach ($Users as $item) {
            User::query()->where('phone', $item->mobile)->update(['z' => $item->z]);
            Customer::query()->where('phone', $item->mobile)->update(['z' => $item->z]);
        }
    }
}

