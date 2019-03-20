<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\Point;
use League\Fractal\TransformerAbstract;

class PointTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['market', 'scene', 'area', 'contract', 'share'];

    public function transform(Point $point)
    {
        return [
            'id' => (int)$point->oid,
            'name' => (string)$point->name,
            'info' => (string)$point->info,
            'icon' => (string)$point->icon,
            'image' => (string)$point->image,
            'type' => (string)$point->type,
            'lat' => (string)$point->lat,
            'lng' => (string)$point->lng,
            'count' => (int)$point->count,
            'marketid' => (int)$point->marketid,
            'areaid' => (int)$point->areaid,
            'attribute_id' => $point->attribute->first() ? $point->attribute->first()->id : null,
            'visiable' => $point->visiable
        ];
    }

    public function includeMarket(Point $point)
    {
        return $this->item($point->market, new MarketTransformer());
    }

    public function includeArea(Point $point)
    {
        $area = $point->area;
        if ($area) {
            return $this->item($point->area, new AreaTransformer());
        }
    }

    public function includeScene(Point $point)
    {
        return $this->item($point->scene, new SceneTransformer());
    }

    public function includeContract(Point $point)
    {
        $contract = $point->contract;
        if ($contract) {
            return $this->item($contract, new PointContractTransformer());
        }
    }

    public function includeShare(Point $point)
    {
        $share = $point->share;
        if ($share) {
            return $this->item($share, new PointShareTransformer());
        }
    }

}