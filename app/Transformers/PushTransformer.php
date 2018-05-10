<?php

namespace App\Transformers;


use App\Models\Push;
use Carbon\Carbon;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class PushTransformer extends TransformerAbstract
{
    protected $availableIncludes=['point'];
    public function transform(Push $push){
        return [
            'id'=>$push->id,
            'img'=>$push->project->icon,
            'area'=>$push->point->area->name,
            'market'=>$push->point->market->name,
            'point'=>$push->point->name,
            'faceDate'=>Carbon::parse(date('Y-m-d H:i:s',($push->facedate)/1000))->diffForHumans(Carbon::now()),
            'networkDate'=>Carbon::parse(date('Y-m-d H:i:s',($push->networkdate)/1000))->diffForHumans(Carbon::now()),
            'screenStatus'=>$push->hdmi,
            'loginDate'=>(new Carbon($push->date))->format('m-d H:i'),
            'on/off_time'=>$push->shours.'-'.$push->ehours.'点',
            'version'=>$push->curversion,
            'system'=>$push->systemversion,
            'device_id'=>(string)$push->did,
        ];
    }

   public function includePoint(Push $push){
        return $this->item($push->point,new PointTransformer());
   }

}