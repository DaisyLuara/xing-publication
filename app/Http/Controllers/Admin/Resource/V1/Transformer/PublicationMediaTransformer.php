<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/5
 * Time: 下午5:16
 */

namespace App\Http\Controllers\Admin\Resource\V1\Transformer;


use App\Http\Controllers\Admin\Resource\V1\Models\PublicationMedia;
use League\Fractal\TransformerAbstract;

class PublicationMediaTransformer extends TransformerAbstract
{
    public function transform(PublicationMedia $publicationMedia): array
    {

        $media = $publicationMedia->media;
        return [
            'id' => $publicationMedia->id,
            'name' => $media->name,
            'url' => $media->url,
        ];
    }
}