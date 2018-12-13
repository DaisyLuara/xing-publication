<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddDownloadToBonusMaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bonusMa = Role::findByName('bonus-manager')->users()->first();
        $bonusMa->givePermissionTo('download');
    }
}
