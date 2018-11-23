<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FacePlayTimesCharacter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:face_playtimes_character';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗7s,15s,21s人次人群特征';

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
        playTimesCharacterClean();
    }
}
