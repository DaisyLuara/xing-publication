<?php

namespace App\Http\Controllers\Admin\Face\V1\Transformer;

use App\Http\Controllers\Admin\Face\V1\Models\FaceCount;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog;
use League\Fractal\TransformerAbstract;

class FaceCountTransformer extends TransformerAbstract
{
    public function transform(XsFaceCountLog $faceCount)
    {
        return [
            'id' => $faceCount->id,
            'point_name' => $faceCount->point_name,
            'market_name' => $faceCount->market_name,
            'area_name' => $faceCount->area_name,
            'looknum' => $faceCount->looknum,
            'playernum7' => $faceCount->playernum7,
            'playernum' => $faceCount->playernum,
            'lovenum' => $faceCount->lovenum,
            'outnum' => $faceCount->outnum,
            'scannum' => $faceCount->scannum,
            'max_date' => date('Y-m-d', $faceCount->max_date / 1000),
            'min_date' => date('Y-m-d', $faceCount->min_date / 1000),
            'projects' => (string)$faceCount->projects,
            'created_at' => $faceCount->created_at->toDateTimeString(),
        ];
    }

}