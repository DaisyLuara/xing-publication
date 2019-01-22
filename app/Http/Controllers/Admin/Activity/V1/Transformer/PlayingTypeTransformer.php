<?php

namespace App\Http\Controllers\Admin\Activity\V1\Transformer;

use App\Http\Controllers\Admin\Activity\V1\Models\PlayingType;
use League\Fractal\TransformerAbstract;

class PlayingTypeTransformer extends TransformerAbstract
{

    public function transform(PlayingType $playingType)
    {
        return [
            'aid' => $playingType->aid,
            'name' => $playingType->name,
        ];
    }

}