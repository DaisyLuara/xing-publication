<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddRoleForProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'invoice', 'display_name' => '票据']);
        Permission::create(['name' => 'payments', 'display_name' => '付款']);
        Permission::create(['name' => 'finance_bill', 'display_name' => '财务开票']);
        Permission::create(['name' => 'finance_pay', 'display_name' => '财务付款']);
        Permission::create(['name' => 'auditing', 'display_name' => '流程审批']);

        $legalManager = Role::create(['name' => 'legal-affairs-manager', 'display_name' => '法务主管']);
        $BDManager = Role::create(['name' => 'bd-manager', 'display_name' => 'BD主管']);
        $finance = Role::create(['name' => 'finance', 'display_name' => '财务']);
        $auditor = Role::create(['name' => 'auditor', 'display_name' => '审计']);

        $legalManager->givePermissionTo(['company', 'project', 'device', 'ad', 'point', 'team', 'report', 'home', 'contract', 'invoice', 'payments']);
        $BDManager->givePermissionTo(['company', 'project', 'device', 'ad', 'point', 'team', 'report', 'home', 'contract', 'invoice', 'payments']);
        $finance->givePermissionTo(['invoice', 'payments']);
        $auditor->givePermissionTo(['payments']);

        $BD = Role::findByName('user');
        $BD->givePermissionTo(['contract', 'invoice', 'payments']);

        $super_admin = Role::findByName('super-admin');
        $super_admin->givePermissionTo(['invoice', 'payments']);

        $legal = Role::findByName('legal-affairs');
        $legal->givePermissionTo(['contract', 'invoice', 'payments']);
    }
}
