<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class AddPermissionAndRoleForExportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

//        storage.product.export
//        storage.store.export
//        storage.location.export
//        storage.records.export
//        storage.list.export
        $storage = Permission::query()->where('name', '=', 'storage')->first()
            ?? Permission::create(['name' => 'storage', 'display_name' => '仓库']);
        $storage_product = Permission::query()->where('name', '=', 'storage.product')->first()
            ?? Permission::create(['name' => "storage.product", 'display_name' => "产品管理", 'parent_id' => $storage->id]);
        $storage_store = Permission::query()->where('name', '=', 'storage.store')->first()
            ?? Permission::create(['name' => "storage.store", 'display_name' => "库位管理", 'parent_id' => $storage->id]);
        $storage_location = Permission::query()->where('name', '=', 'storage.location')->first()
            ?? Permission::create(['name' => "storage.location", 'display_name' => "库存明细", 'parent_id' => $storage->id]);
        $storage_records = Permission::query()->where('name', '=', 'storage.records')->first()
            ?? Permission::create(['name' => "storage.records", 'display_name' => "调拨记录", 'parent_id' => $storage->id]);
        $storage_list = Permission::query()->where('name', '=', 'storage.list')->first()
            ?? Permission::create(['name' => "storage.list", 'display_name' => "仓库管理", 'parent_id' => $storage->id]);


        $storage_data = [
            ['name' => 'storage.product.export', 'display_name' => '下载', 'parent_id' => $storage_product->id],
            ['name' => 'storage.store.export', 'display_name' => '下载', 'parent_id' => $storage_store->id],
            ['name' => 'storage.location.export', 'display_name' => '下载', 'parent_id' => $storage_location->id],
            ['name' => 'storage.records.export', 'display_name' => '下载', 'parent_id' => $storage_records->id],
            ['name' => 'storage.list.export', 'display_name' => '下载', 'parent_id' => $storage_list->id],

        ];
        foreach ($storage_data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }


//        payment.list.export
//        payment.payee.export
//        payment.history.export
        $payment = Permission::query()->where('name', '=', 'payment')->first()
            ?? Permission::create(['name' => 'payment', 'display_name' => '付款']);

        $payment_list = Permission::query()->where('name', '=', 'payment.list')->first()
            ?? Permission::create(['name' => "payment.list", 'display_name' => "付款管理", 'parent_id' => $payment->id]);
        $payment_payee = Permission::query()->where('name', '=', 'payment.payee')->first()
            ?? Permission::create(['name' => "payment.payee", 'display_name' => "收款人管理", 'parent_id' => $payment->id]);
        $payment_history = Permission::query()->where('name', '=', 'payment.history')->first()
            ?? Permission::create(['name' => "payment.history", 'display_name' => "我已审批", 'parent_id' => $payment->id]);

        $payment_export_data = [
            ['name' => 'payment.list.export', 'display_name' => '下载', 'parent_id' => $payment_list->id],
            ['name' => 'payment.payee.export', 'display_name' => '下载', 'parent_id' => $payment_payee->id],
            ['name' => 'payment.history.export', 'display_name' => '下载', 'parent_id' => $payment_history->id],
        ];

        foreach ($payment_export_data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }


//        invoice.list.export
//        invoice.invoiceCompany.export
//        invoice.history.export
//        invoice.receipt.export
        $invoice = Permission::query()->where('name', '=', 'invoice')->first()
            ?? Permission::create(['name' => 'invoice', 'display_name' => '票据']);

        $invoice_list = Permission::query()->where('name', '=', 'invoice.list')->first()
            ?? Permission::create(['name' => "invoice.list", 'display_name' => "开票管理", 'parent_id' => $invoice->id]);
        $invoice_invoiceCompany = Permission::query()->where('name', '=', 'invoice.invoiceCompany')->first()
            ?? Permission::create(['name' => "invoice.invoiceCompany", 'display_name' => "开票公司", 'parent_id' => $invoice->id]);
        $invoice_history = Permission::query()->where('name', '=', 'invoice.history')->first()
            ?? Permission::create(['name' => "invoice.history", 'display_name' => "我已审批", 'parent_id' => $invoice->id]);
        $invoice_receipt = Permission::query()->where('name', '=', 'invoice.receipt')->first()
            ?? Permission::create(['name' => "invoice.receipt", 'display_name' => "收款管理", 'parent_id' => $invoice->id]);

        $invoice_export_data = [
            ['name' => 'invoice.list.export', 'display_name' => '下载', 'parent_id' => $invoice_list->id],
            ['name' => 'invoice.invoiceCompany.export', 'display_name' => '下载', 'parent_id' => $invoice_invoiceCompany->id],
            ['name' => 'invoice.history.export', 'display_name' => '下载', 'parent_id' => $invoice_history->id],
            ['name' => 'invoice.receipt.export', 'display_name' => '下载', 'parent_id' => $invoice_receipt->id],
        ];

        foreach ($invoice_export_data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }


//        demand.application.export
//        demand.modify.export
        $demand = Permission::query()->where('name', '=', 'demand')->first()
            ?? Permission::create(['name' => 'demand', 'display_name' => '需求']);
        $application = Permission::query()->where('name', '=', 'demand.application')->first()
            ?? Permission::create(['name' => "demand.application", 'display_name' => "需求申请", 'parent_id' => $demand->id]);
        $modify = Permission::query()->where('name', '=', 'demand.modify')->first()
            ?? Permission::create(['name' => "demand.modify", 'display_name' => "需求修改", 'parent_id' => $demand->id]);

        $data = [
            ['name' => 'demand.application.export', 'display_name' => '下载', 'parent_id' => $application->id],
            ['name' => 'demand.modify.export', 'display_name' => '下载', 'parent_id' => $modify->id],

        ];
        foreach ($data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }


//        contract.list.export
//        contract.collection.export
//        contract.history.export
//        contract.cost.export
        $contract = Permission::query()->where('name', '=', 'contract')->first()
            ?? Permission::create(['name' => 'contract', 'display_name' => '合同']);

        $contract_list = Permission::query()->where('name', '=', 'contract.list')->first()
            ?? Permission::create(['name' => "contract.list", 'display_name' => "合同管理", 'parent_id' => $contract->id]);
        $contract_collection = Permission::query()->where('name', '=', 'contract.collection')->first()
            ?? Permission::create(['name' => "contract.collection", 'display_name' => "收款合同", 'parent_id' => $contract->id]);
        $contract_history = Permission::query()->where('name', '=', 'contract.history')->first()
            ?? Permission::create(['name' => "contract.history", 'display_name' => "我已审批", 'parent_id' => $contract->id]);
        $contract_cost = Permission::query()->where('name', '=', 'contract.cost')->first()
            ?? Permission::create(['name' => "contract.cost", 'display_name' => "成本合同", 'parent_id' => $contract->id]);

        $contract_export_data = [
            ['name' => 'contract.list.export', 'display_name' => '下载', 'parent_id' => $contract_list->id],
            ['name' => 'contract.collection.export', 'display_name' => '下载', 'parent_id' => $contract_collection->id],
            ['name' => 'contract.history.export', 'display_name' => '下载', 'parent_id' => $contract_history->id],
            ['name' => 'contract.cost.export', 'display_name' => '下载', 'parent_id' => $contract_cost->id],
        ];

        foreach ($contract_export_data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }


        // company.customers.export
        $company = Permission::query()->where('name', '=', 'company')->first()
            ?? Permission::create(['name' => 'company', 'display_name' => '公司']);
        $company_customers = Permission::query()->where('name', '=', 'company.customers')->first()
            ?? Permission::create(['name' => "company.customers", 'display_name' => "公司管理", 'parent_id' => $company->id]);
        $company_export_data = [
            ['name' => 'company.customers.export', 'display_name' => '下载', 'parent_id' => $company_customers->id],
        ];
        foreach ($company_export_data as $item) {
            if (!Permission::query()->where('name', '=', $item['name'])->first()) {
                Permission::create($item);
            }
        }


        $allPermissionData = [
            'storage',
            'payment',
            'invoice',
            'demand',
            'contract',
            'company',
            'storage.product',
            'storage.store',
            'storage.location',
            'storage.records',
            'storage.list',
            'payment.list',
            'payment.payee',
            'payment.history',
            'invoice.list',
            'invoice.invoiceCompany',
            'invoice.history',
            'invoice.receipt',
            'demand.application',
            'demand.modify',
            'contract.list',
            'contract.collection',
            'contract.history',
            'contract.cost',
            'company.customers',
            'storage.product.export',
            'storage.store.export',
            'storage.location.export',
            'storage.records.export',
            'storage.list.export',
            'payment.list.export',
            'payment.payee.export',
            'payment.history.export',
            'invoice.list.export',
            'invoice.invoiceCompany.export',
            'invoice.history.export',
            'invoice.receipt.export',
            'demand.application.export',
            'demand.modify.export',
            'contract.list.export',
            'contract.collection.export',
            'contract.history.export',
            'contract.cost.export',
            'company.customers.export',
        ];


        $admin = Role::query()->where('name', '=', 'admin')->first()
            ?? Role::create(['name' => 'admin', 'display_name' => '管理员']); //管理员
        $legal_affairs_manager = Role::query()->where('name', '=', 'legal-affairs-manager')->first()
            ?? Role::create(['name' => 'legal-affairs-manager', 'display_name' => '法务主管']); //法务主管

        $admin->givePermissionTo($allPermissionData);
        $legal_affairs_manager->givePermissionTo($allPermissionData);

    }
}
