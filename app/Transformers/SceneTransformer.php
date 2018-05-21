<?php

namespace App\Transformers;

use App\Models\Scene;
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