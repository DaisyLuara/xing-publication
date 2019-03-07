<?php

namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {

        return [
            'id' => $user->id,
            'parent_id' => $user->parent_id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'phone' => $user->phone,
        ];
    }

}