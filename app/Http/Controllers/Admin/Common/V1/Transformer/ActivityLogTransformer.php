<?php

namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use Spatie\Activitylog\Models\Activity;
use App\Models\Customer;

class ActivityLogTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['causer'];

    public function transform(Activity $activity)
    {
        return [
            'id' => $activity->id,
            'log_name' => $activity->log_name,
            'description' => $activity->description,
            'subject_id' => $activity->subject_id,
            'subject_type' => $activity->subject_type,
            'ip' => $activity->getExtraProperty('ip') ?? '--',
            'properties' => $activity->getExtraProperty('request_params') ?? $activity->properties,
            'created_at' => $activity->created_at->toDateTimeString(),
            'updated_at' => $activity->updated_at->toDateTimeString(),
        ];
    }

    public function includeCauser(Activity $activity)
    {
        $causer = $activity->causer;
        if ($causer instanceof Customer) {
            $transfomer = new CustomerTransformer();
        } elseif ($causer instanceof User) {
            $transfomer = new UserTransformer();
        } else {
            return;
        }

        return $this->item($activity->causer, $transfomer);
    }
}