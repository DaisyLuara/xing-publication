<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddOperationForProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operation = Role::create(['name' => 'operation', 'display_name' => '运营']);
        $operation->givePermissionTo(['contract', 'invoice', 'payments']);
    }
}
