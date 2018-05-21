<?php

namespace App\Transformers;

use App\Models\FaceCount;
use League\Fractal\TransformerAbstract;

class FaceCountTransformer extends TransformerAbstract
{
    public function transform(FaceCount $faceCount)
    {
        return [
            'id' => $faceCount->id,
            'point_name' => $faceCount->point_name,
            'market_name' => $faceCount->market_name,
            'area_name' => $faceCount->area_name,
            'looknum' => $faceCount->looknum,
            'playernum' => $faceCount->playernum,
            'lovenum' => $faceCount->lovenum,
            'outnum' => $faceCount->outnum,
            'scannum' => $faceCount->scannum,
            'created_at' => $faceCount->created_at->toDateTimeString(),
        ];
    }

}