<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:12
 */

namespace App\Http\Controllers\Admin\Team\V1\Transformer;


use App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject;
use League\Fractal\TransformerAbstract;

class TeamSystemProjectTransformer extends TransformerAbstract
{


    public function transform(TeamSystemProject $teamSystemProject)
    {
        return [
            'id' => $teamSystemProject->id,
            'name' => $teamSystemProject->name,
            'applicant' => $teamSystemProject->applicant,
            'applicant_name' => $teamSystemProject->user->name,
            'status' => (TeamSystemProject::$statusMapping)[$teamSystemProject->status] ?? '未知',
            'remark' => $teamSystemProject->remark,
            'reject_message' => $teamSystemProject->reject_message,
            'created_at' => $teamSystemProject->created_at->toDateString(),
            'updated_at' => $teamSystemProject->updated_at->toDateString()
        ];
    }
}