<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;

class AddOwnerToProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contracts = Contract::query()->get();
        foreach ($contracts as $contract) {
            $contract->update(['owner' => $contract->applicant]);
        }

        $invoices = Invoice::query()->get();
        foreach ($invoices as $invoice) {
            $invoice->update(['owner' => $invoice->applicant]);
        }

        $payments = Payment::query()->get();
        foreach ($payments as $payment) {
            $payment->update(['owner' => $payment->applicant]);
        }

        $demand_applications = DemandApplication::query()->get();
        foreach ($demand_applications as $demand_application) {
            $demand_application->update(['owner' => $demand_application->applicant_id]);
        }
    }
}
