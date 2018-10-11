<?php

namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
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
            'properties' => $activity->properties,
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