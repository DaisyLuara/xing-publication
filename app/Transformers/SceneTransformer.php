<?php

namespace App\Transformers;

use App\Models\Scene;
use League\Fractal\TransformerAbstract;

class SceneTransformer extends TransformerAbstract
{
    public function transform(Scene $scene)
    {
        return [
            'name' => (string)$scene->name,
        ];
    }

}