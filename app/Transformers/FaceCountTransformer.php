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
            'max_date' => date('Y-m-d H:i:s', $faceCount->max_date / 1000),
            'min_date' => date('Y-m-d H:i:s', $faceCount->min_date / 1000),
            'projects' => (string)$faceCount->projects,
            'created_at' => $faceCount->created_at->toDateTimeString(),
        ];
    }

}