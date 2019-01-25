<?php

use Illuminate\Database\Seeder;

class addRoleForShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = DB::table('model_has_roles')->whereRaw("model_type like '%Customer'");

        $ids = DB::table('customers')
            ->leftJoin(DB::raw("({$sql->toSql()}) as a"), 'id', 'model_id')
            ->whereRaw('role_id is null')
            ->selectRaw("id")
            ->get();
        foreach ($ids as $id) {
            DB::table('model_has_roles')->insert(['role_id' => 8, 'model_id' => $id->id, 'model_type' => "App\Models\Customer"]);
        }
    }
}
