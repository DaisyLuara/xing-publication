<?php

namespace App\Http\Controllers\Admin\Skin\V1\Transformer;

use App\Http\Controllers\Admin\Skin\V1\Models\Skin;
use League\Fractal\TransformerAbstract;

class SkinTransformer extends TransformerAbstract
{
    public function transform(Skin $skin)
    {
        return [
            'bid' =>  $skin->bid,
            'fun' => $skin->fun,
            'name' => $skin->name,
            'icon' => $skin->icon,
            'video' => $skin->video,
            'piid' => $skin->piid,
            'marketid' => $skin->marketid,
            'oid' => $skin->oid,
            'credits' => $skin->credits,
            'rmb' => $skin->rmb,
            'url' => $skin->url,
            'size' => $skin->size,
            'nums' => $skin->nums,
            'fopid' => $skin->fopid,
            'fopdate' => $skin->fopdate,
            'code' => $skin->code,
            'pass' => $skin->pass,
            'pass_text' => $skin->getPassText()
        ];
    }
}