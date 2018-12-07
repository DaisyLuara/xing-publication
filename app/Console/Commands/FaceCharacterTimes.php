<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FaceCharacterTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:face_character_times';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗围观人次的时间段与人群特征';

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
        faceCharacterTimesClean();
    }
}
