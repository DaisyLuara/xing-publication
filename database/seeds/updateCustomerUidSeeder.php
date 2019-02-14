<?php

use Illuminate\Database\Seeder;

class updateCustomerUidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = DB::table('customers')->get();
        foreach ($customers as $customer) {
            $staff = DB::connection('ar')->table('admin_staff')->where('mobile', $customer->phone)->first();
            if ($staff) {
                DB::table('customers')->where('id', $customer->id)->update(['ar_user_id'=>$staff->uid]);
            }
        }
    }
}
