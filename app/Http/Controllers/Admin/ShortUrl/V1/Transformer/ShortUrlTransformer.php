<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Transformer;

use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use League\Fractal\TransformerAbstract;
use Hashids\Hashids;

class ShortUrlTransformer extends TransformerAbstract
{

    public function transform(ShortUrl $shortUrl): array
    {
        $hashIds = new Hashids();
        return [
            'id' => $shortUrl->id,
            'url' => env('APP_URL') . '/api/s/' . $hashIds->encode($shortUrl->id),
            'target_url' => $shortUrl->target_url,
            'description' => $shortUrl->description,
            'url_type' => $shortUrl->url_type,
            'created_at' => $shortUrl->created_at->toDateTimeString(),
            'updated_at' => $shortUrl->updated_at->toDateTimeString(),
        ];
    }


}