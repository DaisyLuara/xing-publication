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
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //需求
        $demand_id = Permission::create(['name' => 'demand', 'display_name' => '需求']);

        $application_id = Permission::create(['name' => "demand.application", 'display_name' => "需求申请", 'parent_id' => $demand_id->id]);
        $modify_id = Permission::create(['name' => "demand.modify", 'display_name' => "需求修改", 'parent_id' => $demand_id->id]);

        $data = [
            ['name' => 'demand.application.read', 'display_name' => '查看', 'parent_id' => $application_id->id],
            ['name' => 'demand.application.create', 'display_name' => '新增', 'parent_id' => $application_id->id],
            ['name' => 'demand.application.update', 'display_name' => '修改', 'parent_id' => $application_id->id],
            ['name' => 'demand.application.confirm', 'display_name' => '确认完成', 'parent_id' => $application_id->id],
            ['name' => 'demand.application.receive', 'display_name' => '接单', 'parent_id' => $application_id->id],
            ['name' => 'demand.application.receive_special', 'display_name' => '指定接单', 'parent_id' => $application_id->id],

            ['name' => 'demand.modify.read', 'display_name' => '查看', 'parent_id' => $modify_id->id],
            ['name' => 'demand.modify.create', 'display_name' => '新增', 'parent_id' => $modify_id->id],
            ['name' => 'demand.modify.update', 'display_name' => '修改', 'parent_id' => $modify_id->id],
            ['name' => 'demand.modify.review', 'display_name' => '审核', 'parent_id' => $modify_id->id],
            ['name' => 'demand.modify.feedback', 'display_name' => '反馈', 'parent_id' => $modify_id->id],
        ];
        foreach ($data as $item) {
            Permission::create($item);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        //查看权限
        $readPermissionData = [
            "demand",
            "demand.application",
            "demand.modify",
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

        //指定接单与审核
        $receiveSpecialAndReviewPermissionData = [
            'demand.application.receive_special',
            'demand.modify.review',
        ];

        //确定完成
        $confirmPermissionData = [
            'demand.application.confirm',
        ];


        $BD = Role::findByName('user'); //BD
        $BDManager =Role::findByName('bd-manager'); //BD主管
        $operation = Role::findByName('operation'); //平台运营
        $project_manager = Role::findByName('project-manager'); //产品经理
        $designer = Role::findByName('designer'); //设计
        $bonus_manager= Role::findByName('bonus-manager'); //绩效主管
        $business_operation= Role::findByName('business-operation');  //业务运营
        $legal_affairs_manager = Role::findByName('legal-affairs-manager'); //法务主管

        $BD->givePermissionTo(array_merge($readPermissionData,$editPermissionData,$confirmPermissionData));
        $BDManager->givePermissionTo(array_merge($readPermissionData,$editPermissionData,$confirmPermissionData));
        $business_operation->givePermissionTo(array_merge($readPermissionData,$editPermissionData,$confirmPermissionData));
        $operation->givePermissionTo(array_merge($readPermissionData,$editPermissionData,$confirmPermissionData));
        $project_manager->givePermissionTo(array_merge($readPermissionData,$receiveAndFeedbackPermissionData));
        $designer->givePermissionTo(array_merge($readPermissionData,$receiveAndFeedbackPermissionData));
        $bonus_manager->givePermissionTo(array_merge($readPermissionData));
        $legal_affairs_manager->givePermissionTo(array_merge($readPermissionData,$receiveSpecialAndReviewPermissionData));
    }
}
