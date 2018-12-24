<?php

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Fractal\TransformerAbstract;

class TeamPersonFutureRewardTransformer extends TransformerAbstract
{
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
        $this->start_date = $request->start_date ?? null;
        $this->end_date = $request->end_date ?? null;
    }

    public function transform(TeamPersonFutureReward $teamPersonFutureReward)
    {
        $bug_records = TeamProjectBugRecord::where("belong",$teamPersonFutureReward->belong)
            ->where("user_id",$teamPersonFutureReward->user_id)
            ->selectRaw("date_format(occur_date,'%Y-%m-%d') as occur_date")
            ->get()->toArray();


        $status_money = TeamPersonFutureReward::where('user_id', $teamPersonFutureReward->user_id)
            ->where('belong', $teamPersonFutureReward->belong);
        if ($this->start_date && $this->end_date) {
            $status_money = $status_money->whereBetween("date", [
                Carbon::parse($this->start_date)->toDateString(),
                Carbon::parse($this->end_date)->toDateString()
            ]);
        }
        $status_money = $status_money->groupby("status")
            ->selectRaw("round(sum(total),2) as total_money,status")->pluck('total_money', 'status')->toArray();

        return [
            'id' => $teamPersonFutureReward->id,
            'project_name' => $teamPersonFutureReward->project_name,
            'belong' => $teamPersonFutureReward->belong,
            'freeze_money' => $status_money[0] ?? 0,
            'deduction_money' => $status_money[-1] ?? 0,
            'got_money' => $status_money[1] ?? 0,
            'bug_record' => implode(',',array_column($bug_records,'occur_date')),
        ];
    }
}