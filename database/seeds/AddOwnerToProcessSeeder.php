<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Payment\V1\Models\Payment;

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
    }
}
