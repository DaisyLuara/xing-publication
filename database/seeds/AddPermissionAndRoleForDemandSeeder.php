<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class AddPermissionAndRoleForDemandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //需求
        $demand = Permission::query()->where('name','=','demand')->first()
            ?? Permission::create(['name' => 'demand', 'display_name' => '需求']);
        $application =Permission::query()->where('name','=','demand.application')->first()
            ?? Permission::create(['name' => 'demand.application', 'display_name' => '需求申请', 'parent_id' => $demand->id]);
        $modify = Permission::query()->where('name','=','demand.modify')->first()
            ?? Permission::create(['name' => 'demand.modify', 'display_name' => '需求修改', 'parent_id' => $demand->id]);

        $data = [
            ['name' => 'demand.application.read', 'display_name' => '查看', 'parent_id' => $application->id],
            ['name' => 'demand.application.create', 'display_name' => '新增', 'parent_id' => $application->id],
            ['name' => 'demand.application.update', 'display_name' => '修改', 'parent_id' => $application->id],
            ['name' => 'demand.application.confirm', 'display_name' => '确认完成', 'parent_id' => $application->id],
            ['name' => 'demand.application.receive', 'display_name' => '接单', 'parent_id' => $application->id],
            ['name' => 'demand.application.receive_special', 'display_name' => '指定接单', 'parent_id' => $application->id],

            ['name' => 'demand.modify.read', 'display_name' => '查看', 'parent_id' => $modify->id],
            ['name' => 'demand.modify.create', 'display_name' => '新增', 'parent_id' => $modify->id],
            ['name' => 'demand.modify.update', 'display_name' => '修改', 'parent_id' => $modify->id],
            ['name' => 'demand.modify.review', 'display_name' => '审核', 'parent_id' => $modify->id],
            ['name' => 'demand.modify.feedback', 'display_name' => '反馈', 'parent_id' => $modify->id],
        ];
        foreach ($data as $item) {
            if (!Permission::query()->where('name','=',$item['name'])->first()) {
                Permission::create($item);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        //查看权限
        $readPermissionData = [
            'demand',
            'demand.application',
            'demand.modify',
            'demand.application.read',
            'demand.modify.read',
        ];


        //创建与编辑
        $editPermissionData = [
            'demand.application.create',
            'demand.application.update',

            'demand.modify.create',
            'demand.modify.update',
        ];

        //接单与反馈
        $receiveAndFeedbackPermissionData = [
            'demand.application.receive',
            'demand.modify.feedback',
        ];

        //指定接单
        $receiveSpecialPermissionData = [
            'demand.application.receive_special',
        ];

        //审核
        $reviewPermissionData = [
            'demand.modify.review',
        ];

        //确定完成
        $confirmPermissionData = [
            'demand.application.confirm',
        ];


        $BD = Role::query()->where('name','=','user')->first()
            ?? Role::create(['name'=>'user','display_name'=>'BD']); //BD
        $BDManager = Role::query()->where('name','=','bd-manager')->first()
            ?? Role::create(['name'=>'bd-manager','display_name'=>'BD主管']); //BD主管
        $operation = Role::query()->where('name','=','operation')->first()
            ?? Role::create(['name'=>'operation','display_name'=>'平台运营']); //平台运营
        $project_manager = Role::query()->where('name','=','project-manager')->first()
            ?? Role::create(['name'=>'project-manager','display_name'=>'产品经理']); //产品经理
        $designer = Role::query()->where('name','=','designer')->first()
            ?? Role::create(['name'=>'designer','display_name'=>'设计']); //设计
        $bonus_manager = Role::query()->where('name','=','bonus-manager')->first()
            ?? Role::create(['name'=>'bonus-manager','display_name'=>'绩效主管']); //绩效主管
        $business_operation = Role::query()->where('name','=','business-operation')->first()
            ?? Role::create(['name'=>'business-operation','display_name'=>'业务运营']);  //业务运营
        $legal_affairs_manager = Role::query()->where('name','=','legal-affairs-manager')->first()
            ?? Role::create(['name'=>'legal-affairs-manager','display_name'=>'法务主管']); //法务主管


        $BD->givePermissionTo(array_merge($readPermissionData, $editPermissionData, $confirmPermissionData));
        $BDManager->givePermissionTo(array_merge($readPermissionData, $editPermissionData, $confirmPermissionData));
        $business_operation->givePermissionTo(array_merge($readPermissionData, $editPermissionData, $confirmPermissionData));
        $operation->givePermissionTo(array_merge($readPermissionData, $editPermissionData, $confirmPermissionData, $reviewPermissionData));
        $project_manager->givePermissionTo(array_merge($readPermissionData, $receiveAndFeedbackPermissionData));
        $designer->givePermissionTo(array_merge($readPermissionData, $receiveAndFeedbackPermissionData));
        $bonus_manager->givePermissionTo(array_merge($readPermissionData, $reviewPermissionData));
        $legal_affairs_manager->givePermissionTo(array_merge($readPermissionData, $receiveSpecialPermissionData, $reviewPermissionData));
    }
}
