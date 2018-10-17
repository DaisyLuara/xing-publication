<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FaceLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:face_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '围观渗透率';

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
        faceLogClean();
    }
}
