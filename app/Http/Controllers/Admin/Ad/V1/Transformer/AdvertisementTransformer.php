<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\Advertisement;
use League\Fractal\TransformerAbstract;

class AdvertisementTransformer extends TransformerAbstract
{
    public function transform(Advertisement $advertisement)
    {
        return [
            'id' => $advertisement->aid,
            'adTrade' => $advertisement->adTrade->name,
            'advertiser' => $advertisement->advertiser->name,
            'name' => $advertisement->name,
            'type' => $advertisement->type,
            'img' => $advertisement->img,
            'link' => $advertisement->link,
            'size' => ($advertisement->size) / 1024 / 1024,
            'fps' => $advertisement->fps,
            'isad' => $advertisement->isad,
            'created_at' => $advertisement->date,
            'updated_at' => formatClientDate($advertisement->clientdate),
        ];
    }
}