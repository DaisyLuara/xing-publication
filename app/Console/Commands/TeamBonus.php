<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord;
use Illuminate\Console\Command;

class TeamBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:team_bonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗绩效';

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
        $types_str = '';
        foreach (TeamPersonReward::$mainMapping as $key => $value) {
            $types_str .= $key . ":" . $value . '; ';
        }

        $type = $this->ask("输入清洗绩效的类型(" . $types_str . " )：");

        if ($type == TeamPersonReward::MAIN_TYPE_CPE) {
            teamBonusClean();
        } else if ($type == TeamPersonReward::MAIN_TYPE_PBI) {
            PBIBonusClean();
        } else {
            echo "不存在该种绩效清洗类型";
        }

    }
}
