<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use Illuminate\Console\Command;

class MoveContractFromMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:contract_media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移contract的media到中间表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = Media::query()
            ->whereRaw("contract_id is not null")
            ->selectRaw("id,contract_id")
            ->get();

        foreach ($data as $item) {
            /** @var  $contract \App\Http\Controllers\Admin\Contract\V1\Models\Contract */
            $contract = Contract::query()->withTrashed()->find($item->contract_id);
            if($contract){
                $contract->media()->attach($item->id);
            }
        }
    }
}
