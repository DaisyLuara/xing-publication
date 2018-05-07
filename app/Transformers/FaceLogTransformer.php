<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/7
 * Time: 11:51
 */

namespace App\Transformers;


use App\Models\FaceLog;
use League\Fractal\TransformerAbstract;

class FaceLogTransformer extends TransformerAbstract
{
    public function transform(FaceLog $faceLog)
    {
        return [
            'gender' => [
                'male' => $faceLog->bnum,
                'female' => $faceLog->gnum,
            ],
            'age' => [
                $faceLog->age10,
                $faceLog->age18,
                $faceLog->age30,
                $faceLog->age40,
                $faceLog->age60,
                $faceLog->age61,
            ]
        ];
    }
}