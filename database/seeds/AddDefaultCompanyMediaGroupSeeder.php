<?php

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use Illuminate\Database\Seeder;

class AddDefaultCompanyMediaGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::query()->where('category', 0)->get();
        foreach ($companies as $company) {
            $data[] = [
                'company_id' => $company->id,
                'name' => '默认分组'
            ];
        }
        DB::table('company_media_groups')->insert($data);
    }
}