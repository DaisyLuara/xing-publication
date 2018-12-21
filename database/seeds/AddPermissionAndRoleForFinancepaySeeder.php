<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
class AddPermissionAndRoleForFinancepaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payFinance = Role::create(['name' => 'finance-pay' , 'display_name' => '付款财务']);
        $payFinance->givePermissionTo(['finance_bill', 'finance_pay','invoice','payments']);

    }
}
