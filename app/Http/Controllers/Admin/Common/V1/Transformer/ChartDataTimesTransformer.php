<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/20
 * Time: 下午4:45
 */

namespace App\Http\Controllers\Admin\Common\V1\Transformer;


use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog;
use League\Fractal\TransformerAbstract;

class ChartDataTimesTransformer extends TransformerAbstract
{
    public function transform(XsFaceCountLog $faceCount)
    {
        return [
            'id' => $faceCount->id,
            'point_name' => $faceCount->point_name,
            'market_name' => $faceCount->market_name,
            'area_name' => $faceCount->area_name,
            'looktimes' => $faceCount->looktimes,
            'playtimes7' => $faceCount->playtimes7,
            'playtimes15' => $faceCount->playtimes15,
            'playtimes21' => $faceCount->playtimes21,
            'outnum' => $faceCount->outnum,
            'omo_scannum' => $faceCount->omo_scannum,
            'lovetimes' => $faceCount->lovetimes,
            'verifytimes'=>$faceCount->verifytimes,
            'rate' => [
                'fCPE7' => $faceCount->looktimes == 0 ? 0 : (round($faceCount->playtimes7 / $faceCount->looktimes, 3) * 100) . '%',
                'fCPE15' => $faceCount->looktimes == 0 ? 0 : (round($faceCount->playtimes15 / $faceCount->looktimes, 3) * 100) . '%',
                'fCPE21' => $faceCount->looktimes == 0 ? 0 : (round($faceCount->playtimes21 / $faceCount->looktimes, 3) * 100) . '%',
                'fCPR' => $faceCount->looktimes == 0 ? 0 : (round($faceCount->outnum / $faceCount->looktimes, 3) * 100) . '%',
                'fCPA' => $faceCount->looktimes == 0 ? 0 : (round($faceCount->omo_scannum / $faceCount->looktimes, 3) * 100) . '%',
                'fCPL' => $faceCount->looktimes == 0 ? 0 : (round($faceCount->lovetimes / $faceCount->looktimes, 3) * 100) . '%',
                'fCPS' => $faceCount->looktimes == 0 ? 0 : (round($faceCount->verifytimes / $faceCount->looktimes, 3) * 100) . '%',
            ],
            'max_date' => date('Y-m-d', $faceCount->max_date / 1000),
            'min_date' => date('Y-m-d', $faceCount->min_date / 1000),
            'projects' => (string)$faceCount->projects,
            'created_at' => $faceCount->created_at->toDateTimeString(),
        ];
    }
}