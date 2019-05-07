<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/7
 * Time: 下午4:17
 */

namespace App\Http\Controllers\Admin\Resource\V1\Transformer;


use App\Http\Controllers\Admin\Resource\V1\Models\PublicationMediaGroup;
use League\Fractal\TransformerAbstract;

class PublicationMediaGroupTransformer extends TransformerAbstract
{
    public function transform(PublicationMediaGroup $group): array
    {
        return [
            'id' => $group->id,
            'name' => $group->name
        ];
    }
}