<?php

use App\Models\User;
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
        $Users = DB::connection('ar')->table('admin_staff')->selectRaw('mobile,z')->get();
        foreach ($Users as $item) {
            User::query()->where('phone', $item->mobile)->update(['z' => $item->z]);
        }
    }
}

