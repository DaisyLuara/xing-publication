<?php

namespace App\Transformers;

use App\Models\FaceCount;
use League\Fractal\TransformerAbstract;

class FaceCountDetailTransformer extends TransformerAbstract
{
    public function transform(FaceCount $faceCount)
    {
        return [
            'count' => (int)$faceCount->count,
            'date' => $faceCount->date,
        ];
    }

}