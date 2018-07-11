<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceCollectRecord;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class FaceCharacter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:face_character';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗人群时间性别年龄特征';

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
        //faceCharacterClean();
        faceCharacterCountClean();
    }
}
