<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\Advertisement;
use League\Fractal\TransformerAbstract;

class AdvertisementTransformer extends TransformerAbstract
{
    public function transform(Advertisement $advertisement): array
    {
        $array = [
            'id' => $advertisement->aid,
            'ad_trade_name' => $advertisement->ad_trade ? $advertisement->ad_trade->name : '',
            'create_user_name' => $advertisement->create_user ? $advertisement->create_user->name : '',
            'name' => $advertisement->name,
            'type' => $advertisement->type,
            'type_text' => Advertisement::$typeMapping[$advertisement->type] ?? '未知',
            'img' => $advertisement->img,
            'link' => $advertisement->link,
            'size' => round(((int)$advertisement->size) / 1024 / 1024, 2),
            'fps' => $advertisement->fps,
            'isad' => $advertisement->isad,
            'isad_text' => $advertisement->isad === 1 ? '开启' : '关闭',
            'pass' => $advertisement->pass,
            'created_at' => $advertisement->date,
            'updated_at' => formatClientDate($advertisement->clientdate),
        ];

        if ($advertisement->pivot) {
            $array['pivot'] = $advertisement->pivot->toArray();
        }

        return $array;
    }
}