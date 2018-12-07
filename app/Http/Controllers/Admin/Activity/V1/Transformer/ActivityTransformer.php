<?php

namespace App\Http\Controllers\Admin\Activity\V1\Transformer;

use App\Http\Controllers\Admin\Activity\V1\Models\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{

    public function transform(Activity $activity)
    {
        return [
            'acid' => $activity->acid,
            'cid' => $activity->cid,
            'title' => $activity->title,
            'txt' => $activity->txt,
            'tabs' => $activity->tabs,
            'image' => $activity->image,
            'video' => $activity->video,
            'loc' => $activity->loc,
            'gps' => $activity->gps,
            'awardkey' => $activity->awardkey,
            'oid' => $activity->oid,
            'info' => $activity->info,
            'infolink' => $activity->infolink,
            'ps' => $activity->ps,
            'pslink' => $activity->pslink,
            'date' => $activity->date,
            'clientdate' => $activity->clientdate,
        ];
    }

}