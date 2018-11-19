<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlayerRecord;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class FaceCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:face_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗count数据';

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
        lookTimesClean();
        activePlayerClean();
        activePlayTimesClean();
        omoClean();
        phoneClean();
        mergeActiveOmoLook();
    }
}
