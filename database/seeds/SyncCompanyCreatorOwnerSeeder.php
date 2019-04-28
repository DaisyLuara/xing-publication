<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Company\V1\Models\Company;


class SyncCompanyCreatorOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = DB::table('companies')->where('category', 0)->get();

        foreach ($companies as $company) {
            Company::find($company->id)->update(['bd_user_id'=>$company->user_id]);
        }
    }
}
