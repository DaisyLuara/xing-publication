<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class UpdatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permsData = [
            ['name' => 'home', 'display_name' => '主页'],
            ['name' => 'system', 'display_name' => '设置'],
            ['name' => 'project', 'display_name' => '节目'],
            ['name' => 'market', 'display_name' => '场地'],
            ['name' => 'ad', 'display_name' => '广告'],
            ['name' => 'device', 'display_name' => '设备'],
            ['name' => 'team', 'display_name' => '团队'],
            ['name' => 'report', 'display_name' => '数据'],
            ['name' => 'inform', 'display_name' => '通知'],
            ['name' => 'account', 'display_name' => '账户'],
            ['name' => 'contract', 'display_name' => '合同'],
            ['name' => 'invoice', 'display_name' => '票据'],
            ['name' => 'payment', 'display_name' => '付款'],
            ['name' => 'company', 'display_name' => '公司'],

            ['name' => 'download', 'display_name' => '下载'],
            ['name' => 'finance_bill', 'display_name' => '财务开票'],
            ['name' => 'finance_pay', 'display_name' => '财务付款'],
            ['name' => 'auditing', 'display_name' => '审批'],
            ['name' => 'wechat_card', 'display_name' => '微信卡券'],
        ];
        foreach ($permsData as $item) {
            Permission::create(['name' => $item['name'], 'display_name' => $item['display_name']]);
        }


        #首页
        $home = Permission::findByName('home');
        $homeSecondData = [
            ['name' => 'home.item', 'display_name' => '首页管理']
        ];
        foreach ($homeSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $home->id]);
            $this->createThirdPermission($obj, $item);
        }

        #权限
        $system = Permission::findByName('system');
        $sysSecondData = [
            ['name' => 'system.user', 'display_name' => '用户管理'],
            ['name' => 'system.role', 'display_name' => '角色管理'],
            ['name' => 'system.permission', 'display_name' => '权限管理'],
        ];
        foreach ($sysSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $system->id]);
            $this->createThirdPermission($obj, $item);
        }

        #节目
        $project = Permission::findByName('project');
        $projectSecondData = [
            ['name' => 'project.item', 'display_name' => '节目投放'],
            ['name' => 'project.schedule', 'display_name' => '模版排期'],
            ['name' => 'project.list', 'display_name' => '节目列表'],
            ['name' => 'project.rules', 'display_name' => '优惠券规则'],
            ['name' => 'project.strategy', 'display_name' => '优惠券策略'],
            ['name' => 'project.coupon', 'display_name' => '优惠券'],
        ];
        foreach ($projectSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $project->id]);
            $this->createThirdPermission($obj, $item);
        }

        #场地
        $market = Permission::findByName('market');
        $marketSecondData = [
            ['name' => 'market.site', 'display_name' => '场地管理'],
            ['name' => 'market.point', 'display_name' => '点位管理'],
        ];
        foreach ($marketSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $market->id]);
            $this->createThirdPermission($obj, $item);
        }

        #广告
        $ad = Permission::findByName('ad');
        $adSecondData = [
            ['name' => 'ad.item', 'display_name' => '广告投放'],
            ['name' => 'ad.url', 'display_name' => '短链接']
        ];
        foreach ($adSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $ad->id]);
            $this->createThirdPermission($obj, $item);
        }

        #设备
        $device = Permission::findByName('device');
        $deviceSecondData = [
            ['name' => 'device.item', 'display_name' => '设备管理'],
            ['name' => 'device.map', 'display_name' => '地图总览'],
            ['name' => 'device.feedback', 'display_name' => '数据回流'],
        ];
        foreach ($deviceSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $device->id]);
            $this->createThirdPermission($obj, $item);
        }

        #团队
        $team = Permission::findByName('team');
        $teamSecondData = [
            ['name' => 'team.program', 'display_name' => '节目智造'],
            ['name' => 'team.ratio', 'display_name' => '智造比例'],
            ['name' => 'team.duty', 'display_name' => '重大责任'],
            ['name' => 'team.operation', 'display_name' => '运营文档'],
        ];
        foreach ($teamSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $team->id]);
            $this->createThirdPermission($obj, $item);
        }

        #数据
        $report = Permission::findByName('report');
        $reportSecondData = [
            ['name' => 'report.detail', 'display_name' => '数据详情'],
        ];
        foreach ($reportSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $report->id]);
            $this->createThirdPermission($obj, $item);
        }

        #通知
        $inform = Permission::findByName('inform');
        $informSecondData = [
            ['name' => 'inform.list', 'display_name' => '消息管理'],
            ['name' => 'inform.operate', 'display_name' => '操作记录'],
        ];
        foreach ($informSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $inform->id]);
            $this->createThirdPermission($obj, $item);
        }

        #账户
        $account = Permission::findByName('account');
        $accountSecondData = [
            ['name' => 'account.account', 'display_name' => '账号管理'],
            ['name' => 'account.center', 'display_name' => '个人中心'],
        ];
        foreach ($accountSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $account->id]);
            $this->createThirdPermission($obj, $item);
        }

        #合同
        $contract = Permission::findByName('contract');
        $contractSecondData = [
            ['name' => 'contract.list', 'display_name' => '合同管理'],
            ['name' => 'contract.collection', 'display_name' => '收款合同'],
            ['name' => 'contract.history', 'display_name' => '我已审批'],
        ];
        foreach ($contractSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $contract->id]);
            $this->createThirdPermission($obj, $item);
        }

        #票据
        $invoice = Permission::findByName('invoice');
        $invoiceSecondData = [
            ['name' => 'invoice.list', 'display_name' => '开票管理'],
            ['name' => 'invoice.company', 'display_name' => '开票公司'],
            ['name' => 'invoice.history', 'display_name' => '我已审批'],
            ['name' => 'invoice.receipt', 'display_name' => '收款管理']
        ];
        foreach ($invoiceSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $invoice->id]);
            $this->createThirdPermission($obj, $item);
        }

        #付款
        $payment = Permission::findByName('payment');
        $paymentSecondData = [
            ['name' => 'payment.list', 'display_name' => '付款管理'],
            ['name' => 'payment.payee', 'display_name' => '收款人管理'],
            ['name' => 'payment.history', 'display_name' => '我已审批'],
        ];
        foreach ($paymentSecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $payment->id]);
            $this->createThirdPermission($obj, $item);
        }

        #公司
        $company = Permission::findByName('company');
        $companySecondData = [
            ['name' => 'company.customers', 'display_name' => '公司管理'],
        ];
        foreach ($companySecondData as $item) {
            $obj = Permission::create(['name' => $item['name'], 'display_name' => $item['display_name'], 'parent_id' => $company->id]);
            $this->createThirdPermission($obj, $item);
        }

        $allPermission = Permission::all();
        $superAdmin = Role::findByName('super-admin');
        $superAdmin->givePermissionTo($allPermission);

        $admin = Role::findByName('admin');
        $admin->givePermissionTo($allPermission);

    }

    public function createThirdPermission($obj, $item)
    {
        $data = [
            ['name' => $item['name'] . '.read', 'display_name' => '查看'],
            ['name' => $item['name'] . '.create', 'display_name' => '新增'],
            ['name' => $item['name'] . '.update', 'display_name' => '修改'],
            ['name' => $item['name'] . '.delete', 'display_name' => '删除'],
        ];
        foreach ($data as $aa) {
            Permission::create(array_merge($aa, ['parent_id' => $obj->id]));
        }

    }
}
