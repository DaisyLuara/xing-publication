<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Transformer;

use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use League\Fractal\TransformerAbstract;

class PushTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project'];

    public function transform(ShortUrl $shortUrl)
    {
        return [
        ];
    }


}