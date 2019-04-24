<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddPermissonForDesignerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designer=Role::findByName('designer');
        $designer->givePermissionTo('report');
    }
}
