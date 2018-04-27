<?php

namespace App\Transformers;

use App\Models\FaceCount;
use League\Fractal\TransformerAbstract;

class FaceCountTransformer extends TransformerAbstract
{
    public function transform(FaceCount $faceCount)
    {
        return [
            'looknum' => (int)$faceCount->looknum,
            'playernum' => (int)$faceCount->playernum,
            'lovenum' => (int)$faceCount->lovenum,
            'outnum' => (int)$faceCount->scannum,
            'scannum' => (int)$faceCount->scannum,
        ];
    }

}