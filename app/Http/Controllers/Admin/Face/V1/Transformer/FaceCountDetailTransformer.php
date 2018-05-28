<?php

namespace App\Http\Controllers\Admin\Face\V1\Transformer;

use App\Http\Controllers\Admin\Face\V1\Models\FaceCount;
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