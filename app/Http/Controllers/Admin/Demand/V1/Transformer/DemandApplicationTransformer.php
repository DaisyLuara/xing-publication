<?php

namespace App\Http\Controllers\Admin\Demand\V1\Transformer;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DemandApplicationTransformer extends TransformerAbstract
{
    public function transform(DemandApplication $demandApplication): array
    {
        $expect_receiver_ids = array_map('intval', explode(',', $demandApplication->getExpectReceiverIds()));
        $expect_receiver_names = [];
        if (count($expect_receiver_ids) > 0) {
            $expect_receiver_names = User::query()->whereIn('id', $expect_receiver_ids)
                ->pluck('name')->toArray();
        }

        return [
            'id' => $demandApplication->getId(),
            'title' => $demandApplication->getTitle(),
            'applicant_id' => $demandApplication->getApplicantId(),
            'applicant_name' => $demandApplication->applicant->name,
            'launch_point_remark' => $demandApplication->getLaunchPointRemark(),
            'has_contract' => $demandApplication->getHasContract(),
            'has_contract_text' => $demandApplication->getHasContractText(),
            'contract_ids' => $demandApplication->contracts()->pluck('contracts.id')->toArray(),
            'contract_no_string' => implode(',', $demandApplication->contracts()->pluck('contract_number')->toArray()),
            'project_num' => $demandApplication->getProjectNum(),
            'similar_project_name' => $demandApplication->getSimilarProjectName(),
            'expect_online_time' => Carbon::parse($demandApplication->getExpectOnlineTime())->toDateString(),
            'expect_receiver_ids' => $expect_receiver_ids,
            'expect_receiver_names' => implode(',', $expect_receiver_names),
            'big_screen_demand' => $demandApplication->getBigScreenDemand(),
            'small_screen_demand' => $demandApplication->getSmallScreenDemand(),
            'h5_demand' => $demandApplication->getH5Demand(),
            'other_demand' => $demandApplication->getOtherDemand(),
            'applicant_remark' => $demandApplication->getApplicantRemark(),
            'status' => $demandApplication->getStatus(),
            'status_text' => $demandApplication->getStatusText(),
            'receiver_id' => $demandApplication->getReceiverId(),
            'receiver_name' => $demandApplication->getReceiverName(),
            'receiver_remark' => $demandApplication->getReceiverRemark(),
            'receiver_time' => (string)$demandApplication->getReceiverTime(),
            'confirm_id' => $demandApplication->getConfirmId(),
            'confirm_name' => $demandApplication->getConfirmName(),
            'confirm_time' => (string)$demandApplication->getConfirmTime(),
            'created_at' => (string)$demandApplication->getCreatedAt(),
            'updated_at' => (string)$demandApplication->getUpdatedAt(),
        ];
    }
}