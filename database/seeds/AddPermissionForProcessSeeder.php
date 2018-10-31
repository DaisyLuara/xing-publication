<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class AddPermissionForProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //审批法务
        $legal = User::query()->where('phone', '18301766780')->first();
        $legal->givePermissionTo('auditing');

        //开票财务
        $finance_bill = User::query()->where('phone', '13764554970')->first();
        $finance_bill->givePermissionTo('finance_bill');

        //付款财务
        $finance_pay = User::query()->where('phone', '13764554970')->first();
        $finance_pay->givePermissionTo('finance_pay');

        //审批审计
        $auditor = User::query()->where('phone', '13601928127')->first();
        $auditor->givePermissionTo('auditing');
    }
}