<?php

namespace App\Transformers;


use App\Models\Push;
use Carbon\Carbon;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class PushTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project'];

    public function transform(Push $push)
    {
        return [
            'id' => $push->id,
            'img' => $push->product_img,
            'area' => $push->area_name,
            'market' => $push->market_name,
            'point' => $push->point_name,
            'faceDate' => Carbon::parse(date('Y-m-d H:i:s', ($push->facedate) / 1000))->diffForHumans(Carbon::now()),
            'networkDate' => Carbon::parse(date('Y-m-d H:i:s', ($push->networkdate) / 1000))->diffForHumans(Carbon::now()),
            'screenStatus' => $push->hdmi,
            'loginDate' => (new Carbon($push->date))->format('m-d H:i'),
            'on_time' => $push->shours,
            'off_time' => $push->ehours,
            'version' => $push->curversion,
            'system' => $push->systemversion,
            'device_id' => (string)$push->did,
        ];
    }

    public function includePoint(Push $push)
    {
        return $this->item($push->point, new PointTransformer());
    }

    public function includeProject(Push $push)
    {
        $project = $push->project;
        if ($project) {
            return $this->item($project, new ProjectTransformer());
        }
    }

}