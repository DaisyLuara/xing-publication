<?php

namespace App\Http\Controllers\Admin\Face\V1\Transformer;

use App\Http\Controllers\Admin\Face\V1\Models\FaceCollect;
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