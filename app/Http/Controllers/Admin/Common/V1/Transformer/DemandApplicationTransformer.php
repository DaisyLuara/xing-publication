<?php

namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use League\Fractal\TransformerAbstract;

class DemandApplicationTransformer extends TransformerAbstract
{
    public function transform(DemandApplication $demandApplication)
    {

        return [
            'id' => $demandApplication->getId(),
            'title' => $demandApplication->getTitle(),
            'applicant_id' => $demandApplication->getApplicantId(),
            'applicant_name' => $demandApplication->applicant->name,
            'launch_point_remark' => $demandApplication->getLaunchPointRemark(),
        ];
    }
}