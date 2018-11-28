<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddPermissionAndRoleForTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tester = Role::create(['name' => 'tester', 'display_name' => '测试']);
        $designer = Role::create(['name' => 'designer', 'display_name' => '设计']);

        $tester->givePermissionTo(['company', 'project', 'device', 'ad', 'point', 'team', 'report', 'home', 'contract', 'invoice', 'payments']);
        $designer->givePermissionTo(['home', 'team']);

        $projectMa = Role::findByName('project-manager');
        $projectMa->givePermissionTo(['team']);

    }
}
