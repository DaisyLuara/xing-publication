<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddPermissionForLegalAffairsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $legal = Role::findByName('legal-affairs');
        $legal->givePermissionTo('company');
    }
}
