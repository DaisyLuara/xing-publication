<?php

namespace App\Transformers;

use App\Models\FaceCollect;
use League\Fractal\TransformerAbstract;

class FaceCollectTransformer extends TransformerAbstract
{
    public function transform(FaceCollect $faceCollect)
    {
        return [
            ''
        ];
    }

}