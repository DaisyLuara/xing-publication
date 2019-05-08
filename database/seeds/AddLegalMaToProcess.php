<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddLegalMaToProcess extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $legalMaRole = Role::findByName('legal-affairs-manager');
        $legal = $legalMaRole->users()->first();
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs-manager', 'line' => 'contract']);
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs-manager', 'line' => 'invoice']);
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs-manager', 'line' => 'payment']);
    }
}
