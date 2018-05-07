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
    public function transform(FaceLog $faceLog){
        return [
            'gnum'=>$faceLog->gnum,
            'bnum'=>$faceLog->bum,
            '0-10'=>$faceLog->age10,
            '11-18'=>$faceLog->age18,
            '19-30'=>$faceLog->age30,
            '31-40'=>$faceLog->age40,
            '41-60'=>$faceLog->age60,
            '61岁以上'=>$faceLog->age61
        ];
    }
}