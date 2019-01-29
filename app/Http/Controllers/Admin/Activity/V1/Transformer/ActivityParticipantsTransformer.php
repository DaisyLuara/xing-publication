<?php

namespace App\Http\Controllers\Admin\Activity\V1\Transformer;

use App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant;
use App\Http\Controllers\Admin\User\V1\Transformer\ArMemberInfoTransformer;
use League\Fractal\TransformerAbstract;

class ActivityParticipantsTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['playingType', 'arUserInfo'];

    public function transform(ActivityParticipant $activityParticipant)
    {
        return [
            'auid' => $activityParticipant->auid,
            'uid' => $activityParticipant->uid,
            'username' => $activityParticipant->username,
            'link' => $activityParticipant->link,
            'value' => $activityParticipant->value,
            'kid' => $activityParticipant->kid,
            'pass' => $activityParticipant->pass,
            'created_at' => formatClientDate($activityParticipant->clientdate),
        ];
    }

    public function includePlayingType(ActivityParticipant $activityParticipant)
    {
        $playingType = $activityParticipant->playingType;
        if ($playingType) {
            return $this->item($playingType, new PlayingTypeTransformer());
        }
    }

    public function includeArUserInfo(ActivityParticipant $activityParticipant)
    {
        $arUserInfo = $activityParticipant->arUserInfo;
        if ($arUserInfo) {
            return $this->item($arUserInfo, new ArMemberInfoTransformer());
        }
    }


}