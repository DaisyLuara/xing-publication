<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class FaceMau extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:mau';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '月活玩家清洗';

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
        mauClean();
        mauCleanByMarket();
    }
}
