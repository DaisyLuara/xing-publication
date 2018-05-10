<?php

namespace App\Transformers;

use App\Models\FaceCount;
use League\Fractal\TransformerAbstract;

class FaceCountTransformer extends TransformerAbstract
{
    public function transform(FaceCount $faceCount)
    {
        return [
            [
                'name' => '围观人数',
                'count' => (int)$faceCount->looknum,
                'alias' => 'looknum',
            ],
            [
                'name' => '交互完成人数',
                'count' => (int)$faceCount->playernum,
                'alias' => 'playernum',
            ],
            [
                'name' => '微信扫描人数',
                'count' => (int)$faceCount->scannum,
                'out' => (int)$faceCount->outnum,
                'alias' => 'scannum'
            ],
            [
                'name' => '转化人数',
                'count' => (int)$faceCount->lovenum,
                'alias' => 'lovenum'
            ],
        ];
    }

}