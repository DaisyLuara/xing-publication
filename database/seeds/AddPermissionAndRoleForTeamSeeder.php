<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

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

        $operation = Role::findByName('operation');
        $operation->givePermissionTo('team');

        $bonusMa = Role::create(['name' => 'bonus-manager', 'display_name' => '绩效主管']);
        $bonusMa->givePermissionTo('team');

        $yq = User::query()->where('phone', '15671556667')->first();
        $yq->givePermissionTo('download');
    }
}
