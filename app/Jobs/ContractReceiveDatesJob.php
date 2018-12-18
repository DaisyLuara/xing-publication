<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Notifications\CheckReceipt;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use App\Models\User;

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
    public function handle()
    {
        $data = DB::table('contract_receive_dates')
            ->join('contract_histories','contract_receive_dates.contract_id','=','contract_histories.contract_id')
            ->select ('user_id', 'contract_receive_dates.contract_id', 'receive_date')
            ->whereRaw("receive_date >= date( now( ) )AND receive_date < DATE_ADD( date( now( ) ), INTERVAL 1 DAY )AND receive_status = 0")
            ->get();


        foreach ($data as $item) {

            $user = User::find($item->user_id);
            $contract = Contract::find($item->contract_id);
            $user->notify(new CheckReceipt($contract));

        }
    }
}
