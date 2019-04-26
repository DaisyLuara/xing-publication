<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\User;
use App\Notifications\CheckReceipt;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ContractReceiveDatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $now = Carbon::now()->toDateString();
        $data = DB::table('contract_receive_dates')
            ->whereRaw(" '$now' between date_add(receive_date,interval -3 day) and date_add(receive_date,interval 5 day) and receive_status = 0")
            ->selectRaw('distinct contract_id')
            ->get();
        $legal = User::find(getProcessStaffId('legal-affairs', 'contract'));
        $legalMa = User::find(getProcessStaffId('legal-affairs-manager', 'contract'));
        foreach ($data as $item) {
            $contract = Contract::query()->where('status', 3)->where('id', $item->contract_id)->first();
            if (!$contract)
                continue;
            $contract->applicantUser->notify(new CheckReceipt($contract));
            if ($contract->applicantUser->parent_id) {
                User::find($contract->applicantUser->parent_id)->notify(new CheckReceipt($contract));
            }
            $legal->notify(new CheckReceipt($contract));
            $legalMa->notify(new CheckReceipt($contract));
        }
    }
}
