<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Http\Controllers\Admin\Company\V1\Models\Company;

class AddNewPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'download'], ['name' => 'download', 'display_name' => '下载']);

        $users = User::query()->whereIn('phone', ['13818403072', '15221844081', '13916320677'])->get();

        $users->each(function ($user) {
            if (!$user->hasPermissionTo('download')) {
                $user->givePermissionTo('download');
            }
        });
    }
}
