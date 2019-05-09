<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Resource\V1\Models\PublicationMediaGroup;

class AddDefaultMediaGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PublicationMediaGroup::create(['name'=>'未分组']);
    }
}
