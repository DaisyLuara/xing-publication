<?php

namespace App\Http\Controllers\Admin\User\V1\Transformer;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleTransformer;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['roles'];

    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'parent_id' => $user->parent_id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'tower_access_token' => (string)$user->tower_access_token,
            'bind_weixin' => $user->weixin_openid || $user->weixin_unionid,
            'ar_user_id' => $user->ar_user_id,
            'z' => $user->z,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}