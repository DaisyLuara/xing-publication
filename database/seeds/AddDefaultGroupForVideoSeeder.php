<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Company\V1\Models\Company;

class AddDefaultGroupForVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::query()->where('category', 0)->get();
        $data = [];
        foreach ($companies as $company) {
            $data[] = [
                'company_id' => $company->id,
                'name' => '默认分组',
                'type' => 'video'
            ];
        }
        DB::table('company_media_groups')->insert($data);
    }
}
