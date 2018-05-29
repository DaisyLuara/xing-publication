<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\Scene;
use League\Fractal\TransformerAbstract;

class SceneTransformer extends TransformerAbstract
{
    public function transform(Scene $scene)
    {
        return [
            'id' => (int)$scene->sid,
            'name' => (string)$scene->name,
        ];
    }

}