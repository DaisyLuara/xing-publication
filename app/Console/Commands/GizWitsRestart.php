<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GizWitsAppRestart;
use GuzzleHttp\Exception\ClientException;
use Log;

class GizWitsRestart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:restart {did}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时任务重启';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $did = $this->argument('did');
        GizWitsAppRestart::dispatch($did)->onQueue('restart');
    }
}
