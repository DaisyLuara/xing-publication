<?php

use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use Illuminate\Database\Seeder;

class AddPermissionAndRoleForFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //反馈
        $feedback = Permission::query()->where('name', '=', 'feedback')->first()
            ?? Permission::create(['name' => 'feedback', 'display_name' => '反馈']);

        $feedback_list = Permission::query()->where('name', '=', 'feedback.feedback')->first()
            ?? Permission::create(['name' => "feedback.feedback", 'display_name' => "反馈管理", 'parent_id' => $feedback->id]);

        $data = [
            ['name' => 'feedback.feedback.read', 'display_name' => '查看', 'parent_id' => $feedback_list->id],
            ['name' => 'feedback.feedback.create', 'display_name' => '新增/回复', 'parent_id' => $feedback_list->id],
        ];

        foreach ($data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //权限
        $allPermissionData = [
            "feedback",
            "feedback.feedback",
            "feedback.feedback.read",
            "feedback.feedback.create",
        ];

        $BD = Role::query()->where('name','=','user')->first()
            ?? Role::create(['name'=>'user','display_name'=>'BD']); //BD
        $BDManager = Role::query()->where('name','=','bd-manager')->first()
            ?? Role::create(['name'=>'bd-manager','display_name'=>'BD主管']); //BD主管

        $admin = Role::query()->where('name', '=', 'admin')->first()
            ?? Role::create(['name' => 'admin', 'display_name' => '管理员']); //管理员

        $admin->givePermissionTo($allPermissionData);
        $BD->givePermissionTo($allPermissionData);
        $BDManager->givePermissionTo($allPermissionData);
    }
}
