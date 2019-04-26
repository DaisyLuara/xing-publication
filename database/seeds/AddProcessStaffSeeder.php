<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddProcessStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $legalRole = Role::findByName('legal-affairs');
        $legal = $legalRole->users()->first();
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs', 'line' => 'contract']);
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs', 'line' => 'invoice']);
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs', 'line' => 'payment']);

        $financeRole = Role::findByName('finance');
        $finance = $financeRole->users()->first();
        DB::table('process_staffs')->insert(['user_id' => $finance->id, 'role' => 'finance', 'line' => 'invoice']);
        DB::table('process_staffs')->insert(['user_id' => $finance->id, 'role' => 'finance', 'line' => 'payment']);

        $auditorRole = Role::findByName('auditor');
        $auditor = $auditorRole->users()->first();
        DB::table('process_staffs')->insert(['user_id' => $auditor->id, 'role' => 'auditor', 'line' => 'payment']);

    }
}
