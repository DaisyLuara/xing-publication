<?php

namespace App\Http\Controllers\Admin\Face\V1\Transformer;

use App\Http\Controllers\Admin\Face\V1\Models\FaceLog;
use League\Fractal\TransformerAbstract;

class FaceLogTransformer extends TransformerAbstract
{
    public function transform(FaceLog $faceLog)
    {
        return [
            'gender' => [
                'male' => (int)$faceLog->bnum,
                'female' => (int)$faceLog->gnum,
            ],
            'age' => [
                ['count' => (int)$faceLog->age10, 'age' => '0-10岁'],
                ['count' => (int)$faceLog->age18, 'age' => '11-18岁'],
                ['count' => (int)$faceLog->age30, 'age' => '19-30岁'],
                ['count' => (int)$faceLog->age40, 'age' => '31-40岁'],
                ['count' => (int)$faceLog->age60, 'age' => '41-60岁'],
                ['count' => (int)$faceLog->age61, 'age' => '60岁以上']
            ]
        ];
    }
}