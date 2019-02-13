<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PBIBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:pbi_bonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗pbi绩效';

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
        PBIBonusClean();
    }
}
